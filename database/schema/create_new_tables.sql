-- RodaKita: Additional tables for Trust & Safety features
-- Run this AFTER Forward Engineering tubes_rodakita.mwb
-- Target: MySQL 8.0+

CREATE TABLE IF NOT EXISTS verifikasi_akun (
  id INT NOT NULL AUTO_INCREMENT,
  id_user INT NOT NULL,
  foto_ktp VARCHAR(255) NULL DEFAULT NULL,
  foto_sim VARCHAR(255) NULL DEFAULT NULL,
  foto_selfie VARCHAR(255) NULL DEFAULT NULL,
  status ENUM("unverified","pending","verified","rejected") NOT NULL DEFAULT "unverified",
  catatan_verifikasi TEXT NULL DEFAULT NULL,
  verified_at DATETIME NULL DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  INDEX id_user_fk_verifikasi_idx (id_user ASC),
  CONSTRAINT id_user_fk_verifikasi FOREIGN KEY (id_user) REFERENCES user(id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;

CREATE TABLE IF NOT EXISTS klaim_asuransi (
  id INT NOT NULL AUTO_INCREMENT,
  id_booking INT NOT NULL,
  id_pemilik_mobil INT NOT NULL,
  deskripsi_kerusakan TEXT NOT NULL,
  estimasi_biaya DECIMAL(15,2) NOT NULL,
  biaya_disetujui DECIMAL(15,2) NULL DEFAULT NULL,
  foto_bukti JSON NULL DEFAULT NULL,
  status ENUM("diajukan","ditinjau","disetujui","ditolak","selesai") NOT NULL DEFAULT "diajukan",
  catatan_klaim TEXT NULL DEFAULT NULL,
  submitted_at DATETIME NULL DEFAULT NULL,
  processed_at DATETIME NULL DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  INDEX id_booking_fk_klaim_idx (id_booking ASC),
  INDEX id_pemilik_fk_klaim_idx (id_pemilik_mobil ASC),
  CONSTRAINT id_booking_fk_klaim FOREIGN KEY (id_booking) REFERENCES booking(id),
  CONSTRAINT id_pemilik_fk_klaim FOREIGN KEY (id_pemilik_mobil) REFERENCES user(id)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;
