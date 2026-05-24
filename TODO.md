# TODO - perbaikan redirect role

- [ ] Ubah middleware `CheckRole` supaya saat role tidak cocok:
  - tidak abort 403
  - tetapi redirect balik ke halaman sebelumnya (`back()`) dengan session flash error berisi: `Butuh role X untuk mengakses halaman ini`.
- [ ] Pastikan pesan flash error muncul di layout UI (kalau sudah ada blok error global, manfaatkan).
- [ ] Jalankan uji manual:
  - login role improvement, coba akses route role admin/comodity
  - harus kembali ke halaman sebelumnya + muncul pesan butuh role ...

