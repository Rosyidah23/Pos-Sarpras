<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/**
 * Akun pelapor (siswa & guru). Tidak ada pendaftaran mandiri, semua dibuat petugas.
 */
class StudentAccountController extends Controller
{
    public function index()
    {
        $accounts = User::whereIn('role', User::REPORTER_ROLES)
            ->withCount('reports')
            ->orderBy('name')
            ->get();

        return view('admin.accounts', [
            'accounts' => $accounts,
            'roleCounts' => [
                'semua' => $accounts->count(),
                'siswa' => $accounts->where('role', 'siswa')->count(),
                'guru' => $accounts->where('role', 'guru')->count(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', Rule::in(User::REPORTER_ROLES)],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        $label = $data['role'] === 'guru' ? 'guru' : 'siswa';

        return back()->with('status', "Akun {$label} \"{$data['name']}\" berhasil dibuat.");
    }

    /** Batas aman satu kali impor (hashing password butuh waktu per akun). */
    private const IMPORT_LIMIT = 1000;

    /**
     * Impor banyak akun sekaligus.
     * Sumber: file Excel (.xlsx), Word (.docx; tabel atau satu akun per baris), .csv/.txt, atau data yang ditempel.
     * Kolom: nama, email, peran (siswa/guru, wajib diisi di file), password (opsional; kosong = password yang diketik petugas untuk semua, atau diacak per akun kalau tidak diketik).
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => ['nullable', 'file', 'max:5120'],
            'paste' => ['nullable', 'string', 'max:300000'],
            'common_password' => ['nullable', 'string', 'min:6', 'max:100'],
        ], [
            'common_password.min' => 'Password minimal 6 karakter (atau kosongkan supaya diacak).',
        ]);

        @set_time_limit(0);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $ext = strtolower($file->getClientOriginalExtension());
            $path = $file->getRealPath();

            try {
                $rows = match (true) {
                    in_array($ext, ['xlsx', 'xlsm'], true) => $this->rowsFromXlsx($path),
                    $ext === 'docx' => $this->rowsFromDocx($path),
                    in_array($ext, ['csv', 'txt'], true) => $this->parseRows((string) file_get_contents($path)),
                    in_array($ext, ['xls', 'doc'], true) => throw new \RuntimeException('Format lama (.xls / .doc) belum bisa dibaca. Buka filenya, lalu Simpan Sebagai .xlsx (Excel) atau .docx (Word).'),
                    default => throw new \RuntimeException('Format file belum didukung. Pakai Excel (.xlsx) atau Word (.docx).'),
                };
            } catch (\RuntimeException $e) {
                return $this->importError($request, $e->getMessage());
            }
        } elseif (filled($request->input('paste'))) {
            $rows = $this->parseRows((string) $request->input('paste'));
        } else {
            return $this->importError($request, 'Pilih file Excel atau Word dulu.');
        }

        if ($rows === []) {
            return $this->importError($request, 'Tidak ada data yang terbaca di file. Pastikan ada kolom nama dan email.');
        }

        // Baris pertama dianggap judul kolom kalau tidak ada tanda "@" (bukan email).
        $map = ['name' => 0, 'email' => 1, 'role' => 2, 'password' => 3];
        $hasHeader = ! str_contains(implode(' ', $rows[0]['cells']), '@');

        if ($hasHeader) {
            $header = array_shift($rows);
            $map = [];
            $aliases = [
                'name' => ['nama', 'name', 'nama lengkap'],
                'email' => ['email', 'e-mail', 'username'],
                'role' => ['peran', 'role'],
                'password' => ['password', 'kata sandi', 'sandi'],
            ];
            foreach ($header['cells'] as $index => $cell) {
                $cell = mb_strtolower(trim($cell));
                foreach ($aliases as $key => $names) {
                    if (in_array($cell, $names, true) && ! isset($map[$key])) {
                        $map[$key] = $index;
                    }
                }
            }
            if (! isset($map['name'], $map['email'], $map['role'])) {
                return $this->importError($request, 'Judul kolom harus punya "nama", "email", dan "peran". Atau hapus baris judul, lalu isi urut: nama, email, peran, password.');
            }
        }

        if (count($rows) > self::IMPORT_LIMIT) {
            return $this->importError($request, 'Maksimal '.self::IMPORT_LIMIT.' akun per sekali impor. Bagi datanya jadi beberapa file.');
        }

        // Baris tanpa password sendiri: kalau petugas mengetik password, dipakai untuk semuanya; kalau kosong, diacak per akun.
        $sharedPassword = filled($request->input('common_password')) ? (string) $request->input('common_password') : null;
        $sharedHash = null;
        $taken = User::pluck('email')->map(fn ($e) => mb_strtolower($e))->flip();
        $insert = [];
        $created = [];
        $skipped = [];
        $now = now();

        foreach ($rows as $row) {
            $cells = $row['cells'];
            $name = trim($cells[$map['name']] ?? '');
            $email = mb_strtolower(trim($cells[$map['email']] ?? ''));
            $role = '';
            $password = '';

            if ($hasHeader) {
                $role = mb_strtolower(trim($cells[$map['role']] ?? ''));
                $password = trim(isset($map['password']) ? ($cells[$map['password']] ?? '') : '');
            } else {
                // Tanpa judul kolom: urutannya nama, email, peran, password.
                $role = mb_strtolower(trim($cells[2] ?? ''));
                $password = trim($cells[3] ?? '');
            }

            if (! in_array($role, User::REPORTER_ROLES, true)) {
                $skipped[] = [$row['line'], $name ?: $email, 'peran harus diisi "siswa" atau "guru"'];
                continue;
            }

            $reason = null;
            if ($name === '' || mb_strlen($name) > 255) {
                $reason = 'nama kosong atau terlalu panjang';
            } elseif (! filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 255) {
                $reason = 'email tidak valid';
            } elseif ($taken->has($email)) {
                $reason = 'email sudah terdaftar';
            } elseif ($password !== '' && mb_strlen($password) < 6) {
                $reason = 'password minimal 6 karakter';
            }

            if ($reason !== null) {
                $skipped[] = [$row['line'], $name ?: $email, $reason];
                continue;
            }

            if ($password === '') {
                $password = $sharedPassword ?? $this->randomPassword();
            }

            // Password yang sama cukup di-hash sekali (jauh lebih cepat untuk ratusan akun).
            if ($sharedPassword !== null && $password === $sharedPassword) {
                $hash = $sharedHash ??= Hash::make($password, ['rounds' => 10]);
            } else {
                $hash = Hash::make($password, ['rounds' => 10]);
            }

            $taken->put($email, true);
            $insert[] = [
                'name' => $name,
                'email' => $email,
                'password' => $hash,
                'role' => $role,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $created[] = [$name, $email, $role === 'guru' ? 'Guru' : 'Siswa', $password];
        }

        DB::transaction(function () use ($insert) {
            foreach (array_chunk($insert, 100) as $chunk) {
                DB::table('users')->insert($chunk);
            }
        });

        $message = count($created).' akun berhasil dibuat'.(count($skipped) ? ', '.count($skipped).' baris dilewati.' : '.');

        return back()->with([
            'status' => $message,
            'import_result' => ['created' => $created, 'skipped' => $skipped],
        ]);
    }

    /** @return list<array{line:int,cells:list<string>}> */
    private function rowsFromXlsx(string $path): array
    {
        $zip = $this->openZip($path);

        // Cari sheet pertama lewat workbook.xml; kalau gagal, pakai sheet1.xml.
        $sheetPath = 'xl/worksheets/sheet1.xml';
        $workbook = $this->readEntry($zip, 'xl/workbook.xml');
        $rels = $this->readEntry($zip, 'xl/_rels/workbook.xml.rels');
        if ($workbook !== false && $rels !== false) {
            $wb = new \DOMDocument();
            $rl = new \DOMDocument();
            if (@$wb->loadXML($workbook, LIBXML_NONET) && @$rl->loadXML($rels, LIBXML_NONET)) {
                $sheet = $wb->getElementsByTagNameNS('*', 'sheet')->item(0);
                $rid = $sheet?->getAttributeNS('http://schemas.openxmlformats.org/officeDocument/2006/relationships', 'id');
                if ($rid) {
                    foreach ($rl->getElementsByTagNameNS('*', 'Relationship') as $rel) {
                        if ($rel->getAttribute('Id') === $rid) {
                            $target = ltrim($rel->getAttribute('Target'), '/');
                            $sheetPath = str_starts_with($target, 'xl/') ? $target : 'xl/'.$target;
                        }
                    }
                }
            }
        }

        $sheetXml = $this->readEntry($zip, $sheetPath);
        if ($sheetXml === false) {
            $zip->close();
            throw new \RuntimeException('Sheet pertama di file Excel tidak terbaca.');
        }

        $shared = [];
        $sharedXml = $this->readEntry($zip, 'xl/sharedStrings.xml');
        if ($sharedXml !== false) {
            $sd = new \DOMDocument();
            if (@$sd->loadXML($sharedXml, LIBXML_NONET)) {
                foreach ($sd->getElementsByTagNameNS('*', 'si') as $si) {
                    $shared[] = $this->xmlText($si);
                }
            }
        }
        $zip->close();

        $doc = new \DOMDocument();
        if (! @$doc->loadXML($sheetXml, LIBXML_NONET)) {
            throw new \RuntimeException('Isi file Excel tidak bisa dibaca.');
        }

        $rows = [];
        $autoRow = 0;
        foreach ($doc->getElementsByTagNameNS('*', 'row') as $rowEl) {
            $autoRow++;
            $rowNumber = (int) $rowEl->getAttribute('r') ?: $autoRow;
            $byColumn = [];
            $autoCol = 0;

            foreach ($rowEl->childNodes as $cell) {
                if ($cell->nodeType !== XML_ELEMENT_NODE || $cell->localName !== 'c') {
                    continue;
                }
                $col = $this->columnIndex($cell->getAttribute('r'), $autoCol);
                $autoCol = $col + 1;
                $type = $cell->getAttribute('t');
                $raw = '';
                $inline = '';
                foreach ($cell->childNodes as $child) {
                    if ($child->nodeType !== XML_ELEMENT_NODE) {
                        continue;
                    }
                    if ($child->localName === 'v') {
                        $raw = $child->textContent;
                    } elseif ($child->localName === 'is') {
                        $inline = $this->xmlText($child);
                    }
                }

                $value = match ($type) {
                    's' => $shared[(int) $raw] ?? '',
                    'inlineStr' => $inline,
                    default => $raw,
                };

                // Angka seperti NIS bisa tersimpan sebagai 1.2345E+11; kembalikan jadi bilangan bulat biasa.
                if (($type === '' || $type === 'n') && is_numeric($value) && preg_match('/[eE.]/', $value)) {
                    $float = (float) $value;
                    if (floor($float) == $float && abs($float) < 1e15) {
                        $value = (string) (int) $float;
                    }
                }

                $byColumn[$col] = trim($value);
            }

            if ($byColumn === [] || implode('', $byColumn) === '') {
                continue;
            }

            $cells = [];
            for ($i = 0; $i <= max(array_keys($byColumn)); $i++) {
                $cells[] = $byColumn[$i] ?? '';
            }
            $rows[] = ['line' => $rowNumber, 'cells' => $cells];

            if (count($rows) > self::IMPORT_LIMIT + 1) {
                break;
            }
        }

        return $rows;
    }

    /** @return list<array{line:int,cells:list<string>}> */
    private function rowsFromDocx(string $path): array
    {
        $zip = $this->openZip($path);
        $xml = $this->readEntry($zip, 'word/document.xml');
        $zip->close();

        $doc = new \DOMDocument();
        if ($xml === false || ! @$doc->loadXML($xml, LIBXML_NONET)) {
            throw new \RuntimeException('Isi file Word tidak bisa dibaca. Pastikan formatnya .docx.');
        }

        // Utamakan tabel Word: satu baris tabel = satu akun, satu kolom = satu data.
        $rows = [];
        $lineNo = 0;
        foreach ($doc->getElementsByTagNameNS('*', 'tbl') as $table) {
            $nested = false;
            for ($p = $table->parentNode; $p; $p = $p->parentNode) {
                if ($p->localName === 'tbl') {
                    $nested = true;
                    break;
                }
            }
            if ($nested) {
                continue;
            }

            foreach ($table->childNodes as $tr) {
                if ($tr->nodeType !== XML_ELEMENT_NODE || $tr->localName !== 'tr') {
                    continue;
                }
                $lineNo++;
                $cells = [];
                foreach ($tr->childNodes as $tc) {
                    if ($tc->nodeType === XML_ELEMENT_NODE && $tc->localName === 'tc') {
                        $cells[] = trim(preg_replace('/\s+/u', ' ', $this->wordText($tc)));
                    }
                }
                if (implode('', $cells) !== '') {
                    $rows[] = ['line' => $lineNo, 'cells' => $cells];
                }
                if (count($rows) > self::IMPORT_LIMIT + 1) {
                    return $rows;
                }
            }
        }

        if ($rows !== []) {
            return $rows;
        }

        // Tidak ada tabel: anggap tiap paragraf satu akun ("nama, email, peran, password").
        $lines = [];
        foreach ($doc->getElementsByTagNameNS('*', 'p') as $paragraph) {
            $text = trim($this->wordText($paragraph));
            if ($text !== '') {
                $lines[] = $text;
            }
        }

        return $this->parseRows(implode("\n", $lines));
    }

    private function openZip(string $path): \ZipArchive
    {
        if (! class_exists(\ZipArchive::class)) {
            throw new \RuntimeException('Ekstensi ZIP di PHP server belum aktif, jadi file Excel/Word belum bisa dibaca. Aktifkan extension=zip di php.ini.');
        }

        $zip = new \ZipArchive();
        if ($zip->open($path) !== true) {
            throw new \RuntimeException('File tidak bisa dibuka. Pastikan formatnya .xlsx atau .docx yang valid (bukan .xls / .doc lama).');
        }

        return $zip;
    }

    /** Baca satu bagian di dalam file zip, dengan batas ukuran supaya aman. */
    private function readEntry(\ZipArchive $zip, string $name): string|false
    {
        $stat = $zip->statName($name);
        if ($stat === false || $stat['size'] > 30 * 1024 * 1024) {
            return false;
        }

        return $zip->getFromName($name);
    }

    /** Gabungan teks <t> (tanpa catatan fonetik) dari sebuah node XML Excel. */
    private function xmlText(\DOMNode $node): string
    {
        $out = '';
        foreach ($node->getElementsByTagNameNS('*', 't') as $t) {
            if ($t->parentNode && $t->parentNode->localName === 'rPh') {
                continue;
            }
            $out .= $t->textContent;
        }

        return $out;
    }

    /** Teks dari paragraf/sel Word; paragraf di dalam sel digabung dengan spasi, tab dipertahankan. */
    private function wordText(\DOMNode $node): string
    {
        $out = '';
        $walk = function (\DOMNode $parent) use (&$walk, &$out) {
            foreach ($parent->childNodes as $child) {
                if ($child->nodeType !== XML_ELEMENT_NODE) {
                    continue;
                }
                switch ($child->localName) {
                    case 't':
                        $out .= $child->textContent;
                        break;
                    case 'tab':
                        $out .= "\t";
                        break;
                    case 'br':
                    case 'cr':
                        $out .= ' ';
                        break;
                    case 'p':
                        $walk($child);
                        $out .= ' ';
                        break;
                    default:
                        $walk($child);
                }
            }
        };
        $walk($node);

        return $out;
    }

    /** "C5" → 2 (indeks kolom mulai dari 0). */
    private function columnIndex(string $ref, int $fallback): int
    {
        if (! preg_match('/^([A-Za-z]+)/', $ref, $m)) {
            return $fallback;
        }
        $n = 0;
        foreach (str_split(strtoupper($m[1])) as $ch) {
            $n = $n * 26 + (ord($ch) - 64);
        }

        return $n - 1;
    }

    private function importError(Request $request, string $message)
    {
        return back()->withErrors(['import' => $message])->withInput($request->except('file'));
    }

    /** @return list<array{line:int,cells:list<string>}> */
    private function parseRows(string $raw): array
    {
        $raw = preg_replace('/^\xEF\xBB\xBF/', '', $raw);
        if (! mb_check_encoding($raw, 'UTF-8')) {
            $raw = mb_convert_encoding($raw, 'UTF-8', 'Windows-1252');
        }

        $lines = preg_split('/\r\n|\n|\r/', $raw);

        // Pemisah dideteksi dari baris pertama yang terisi: tab (tempel dari Excel), titik koma, atau koma.
        $delimiter = ',';
        foreach ($lines as $line) {
            if (trim($line) === '') {
                continue;
            }
            $counts = ["\t" => substr_count($line, "\t"), ';' => substr_count($line, ';'), ',' => substr_count($line, ',')];
            arsort($counts);
            $delimiter = array_key_first($counts);
            break;
        }

        $rows = [];
        foreach ($lines as $index => $line) {
            if (trim($line) === '') {
                continue;
            }
            $cells = array_map('trim', str_getcsv($line, $delimiter, '"', ''));
            if (implode('', $cells) === '') {
                continue;
            }
            $rows[] = ['line' => $index + 1, 'cells' => $cells];
        }

        return $rows;
    }

    /** 8 karakter huruf+angka tanpa yang mirip (0/O, 1/l), sama seperti tombol "Buatkan acak". */
    private function randomPassword(): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
        $password = '';
        for ($i = 0; $i < 8; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $password;
    }

    public function resetPassword(Request $request, User $user)
    {
        abort_if($user->isPetugas(), 403);

        $data = $request->validate([
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user->update(['password' => Hash::make($data['password'])]);

        return back()->with('status', "Password akun \"{$user->name}\" berhasil diganti.");
    }

    public function destroy(User $user)
    {
        abort_if($user->isPetugas(), 403);

        $user->delete();

        return back()->with('status', "Akun \"{$user->name}\" berhasil dihapus. Laporan yang sudah dibuat tetap tersimpan.");
    }
}
