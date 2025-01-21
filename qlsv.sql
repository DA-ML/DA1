
CREATE TABLE SinhVien (
    mssv CHAR(10) PRIMARY KEY,
    password_sv VARCHAR(255),
    tensv VARCHAR(100) CHARACTER SET utf8mb4,
    ngaysinh DATE,
	emailsv VARCHAR(50),
    hedaotao NVARCHAR(50)
);

CREATE TABLE GiaoVien (
    msgv CHAR(10) PRIMARY KEY,
    password_gv VARCHAR(50),
    tengv VARCHAR(100) CHARACTER SET utf8mb4,
	emailgv VARCHAR(50),
	ngaysinh DATE,
    khoa NVARCHAR(100)
);

CREATE TABLE HocKy (
	mahk CHAR(10) PRIMARY KEY,
	tenhk NVARCHAR(10),
	namhoc NVARCHAR(10)
);

CREATE TABLE Khoa (
	makhoa CHAR(10) PRIMARY KEY,
	tenkhoa NVARCHAR(100)
);

CREATE TABLE LopHoc (
	malop CHAR(10) PRIMARY KEY,
    tenlop VARCHAR(100) CHARACTER SET utf8mb4,
	makhoa CHAR(10),
	mota TEXT,
	FOREIGN KEY (makhoa) REFERENCES Khoa(makhoa)
);

CREATE TABLE ChuanDauRa (
    id CHAR(10) PRIMARY KEY,
    chuan VARCHAR(10)
);

CREATE TABLE ThanhPhanDanhGia (
    id CHAR(10) PRIMARY KEY,
    thanhphan VARCHAR(10),
	tile  DECIMAL(5, 2)
);

CREATE TABLE QuanLyHS (
	mssv CHAR(10),
	malop CHAR(10),
	mahk CHAR(10),
	FOREIGN KEY (mssv) REFERENCES SinhVien(mssv),
	FOREIGN KEY(malop) REFERENCES LopHoc(malop),
	FOREIGN KEY(mahk) REFERENCES HocKy(mahk)
);

CREATE TABLE QuanLyGV (
	msgv CHAR(10),
	malop CHAR(10),
	mahk CHAR(10),
	FOREIGN KEY (msgv) REFERENCES GiaoVien(msgv),
	FOREIGN KEY(mahk) REFERENCES HocKy(mahk),
	FOREIGN KEY(malop) REFERENCES LopHoc(malop)
);

CREATE TABLE QuanLyTPDG (
    thanhphan_id CHAR(10),
    chuan_id CHAR(10),
    FOREIGN KEY (thanhphan_id) REFERENCES ThanhPhanDanhGia(id),
    FOREIGN KEY (chuan_id) REFERENCES ChuanDauRa(id)
);

CREATE TABLE BaiGiang (
	msbg INT AUTO_INCREMENT PRIMARY KEY,
    	tenbg VARCHAR(100) CHARACTER SET utf8mb4,
	file_paths TEXT NULL,
		link_paths TEXT NULL,
	noidungbg TEXT,
	malop CHAR(10),
	FOREIGN KEY (malop) REFERENCES LopHoc(malop)
);

CREATE TABLE BaiKiemTra (
    msbkt INT AUTO_INCREMENT PRIMARY KEY,
    tenbkt VARCHAR(100) CHARACTER SET utf8mb4,
	ngaybatdau DATETIME,
   	ngayketthuc DATETIME,
	file_path TEXT NULL,
	danhgia_id CHAR(10),
	loai_bkt ENUM('TracNghiem', 'TuLuan') NOT NULL,
	num_ques INT,
	thoigianlambai INT,
	FOREIGN KEY (danhgia_id) REFERENCES ThanhPhanDanhGia(id),
	CHECK (ngaybatdau < ngayketthuc),
	malop CHAR(10),
	solanlam INT,
	FOREIGN KEY (malop) REFERENCES LopHoc(malop)
);

CREATE TABLE CauHoi (
    msch INT AUTO_INCREMENT PRIMARY KEY,
	chuan_id CHAR(10),
	dapan CHAR(5),
	diem FLOAT,
	msbkt INT,
	FOREIGN KEY (msbkt) REFERENCES BaiKiemTra(msbkt),
	FOREIGN KEY (chuan_id) REFERENCES ChuanDauRa(id)
);

CREATE TABLE LichSuLamBaiKiemTra (
    id INT AUTO_INCREMENT PRIMARY KEY,
    msbkt INT,
    mssv CHAR(10),
	malop CHAR(10),
    solanlam INT DEFAULT 0,
    FOREIGN KEY (msbkt) REFERENCES BaiKiemTra(msbkt),
	FOREIGN KEY(malop) REFERENCES LopHoc(malop),
    FOREIGN KEY (mssv) REFERENCES SinhVien(mssv)
);

CREATE TABLE KetQuaBaiKiemTra (
    id INT AUTO_INCREMENT PRIMARY KEY,
    msbkt INT,
    mssv CHAR(10),
    diem FLOAT,
    cau_tra_loi JSON,
    files_path TEXT NULL,
    lich_su_id INT,
    FOREIGN KEY (msbkt) REFERENCES BaiKiemTra(msbkt),
    FOREIGN KEY (mssv) REFERENCES SinhVien(mssv),
    FOREIGN KEY (lich_su_id) REFERENCES LichSuLamBaiKiemTra(id)
);

CREATE TABLE SinhVienKetQua (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mssv CHAR(10),
    msbkt INT,
    malop CHAR(10),
	updated_at DATETIME,
	created_at DATETIME,
    FOREIGN KEY (mssv) REFERENCES SinhVien(mssv),
    FOREIGN KEY (msbkt) REFERENCES BaiKiemTra(msbkt),
    FOREIGN KEY (malop) REFERENCES LopHoc(malop)
);

CREATE TABLE KetQuaChuans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sinhvien_ketqua_id INT,
    chuan_id CHAR(10),
    so_cau_dung FLOAT DEFAULT 0,
	updated_at DATETIME,
	created_at DATETIME,
    FOREIGN KEY (sinhvien_ketqua_id) REFERENCES SinhVienKetQua(id),
    FOREIGN KEY (chuan_id) REFERENCES ChuanDauRa(id)
);

CREATE TABLE NhanXetBaiKiemTra (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ketqua_id INT,
    msgv CHAR(10),
    nhanxet TEXT,
    thoigian DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ketqua_id) REFERENCES KetQuaBaiKiemTra(id),
    FOREIGN KEY (msgv) REFERENCES GiaoVien(msgv)
);

CREATE TABLE BaiKiemTraTile (
    msbkt INT,
    malop CHAR(10),
    tile DECIMAL(5, 2),
    PRIMARY KEY (msbkt, malop),
    FOREIGN KEY (msbkt) REFERENCES BaiKiemTra(msbkt),
    FOREIGN KEY (malop) REFERENCES LopHoc(malop),
    CHECK (tile >= 0 AND tile <= 100)
);

CREATE TABLE KetQuaThanhPhan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mssv CHAR(10),
    malop CHAR(10),
    thanhphan_id CHAR(10),
    chuan_id CHAR(10),
    tyle DECIMAL(5, 2),
    FOREIGN KEY (mssv) REFERENCES SinhVien(mssv),
    FOREIGN KEY (malop) REFERENCES LopHoc(malop),
    FOREIGN KEY (thanhphan_id) REFERENCES ThanhPhanDanhGia(id),
    FOREIGN KEY (chuan_id) REFERENCES ChuanDauRa(id)
);

CREATE TABLE MoTa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    malop CHAR(10),
    mota TEXT,
    image_path TEXT,
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (malop) REFERENCES LopHoc(malop) ON DELETE CASCADE
);

CREATE TABLE ThongBao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mssv CHAR(10),
    msbkt INT,
    message TEXT,
    is_read BOOLEAN DEFAULT FALSE,
    updated_at DATETIME,
	created_at DATETIME,
    FOREIGN KEY (msbkt) REFERENCES BaiKiemTra(msbkt) ON DELETE CASCADE
);

INSERT INTO SinhVien (mssv, password_sv, tensv, ngaysinh, emailsv, hedaotao)
VALUES
('SV001', 'svpass123', 'Nguyễn Văn An', '2004-05-15', '22520857@gm.uit.edu.vn','Chính quy'),
('SV002', 'svpass456', 'Trần Thị Bình', '2003-08-22', '22520778@gm.uit.edu.vn', 'Chính quy'),
('SV003', 'svpass789', 'Lê Văn Cao', '2002-12-01', '22520689@gm.uit.edu.vn', 'Từ xa'),
('SV004', 'svpass101', 'Phạm Thị Dương', '2003-03-15', '22520934@gm.uit.edu.vn', 'Chính quy'),
('SV005', 'svpass112', 'Ngô Quang Em', '2001-07-25', '22520812@gm.uit.edu.vn', 'Chính quy'),
('SV006', 'svpass131', 'Đỗ Minh Phúc', '2004-10-10', '22520722@gm.uit.edu.vn', 'Từ xa');

INSERT INTO GiaoVien (msgv, password_gv, tengv, ngaysinh, emailgv, khoa)
VALUES
('GV001', 'gvpass123', 'Lê Văn Cường', '1990-05-27', 'cuonglv@gmail.com','CNPM'),
('GV002', 'gvpass456', 'Phạm Thị Thủy',  '1999-05-26', 'thuypt@gmail.com','CNPM'),
('GV003', 'gvpass789', 'Nguyễn Văn Thanh',  '1998-05-24', 'thanhnv@gmail.com', 'HTTT');

INSERT INTO Khoa(makhoa, tenkhoa)
VALUES
('MK001', 'CNPM'),
('MK002', 'HTTT'),
('MK003', 'MMT&TT'),
('MK004', 'KHMT');

INSERT INTO HocKy(mahk, tenhk, namhoc)
VALUES
('HK001', '1', '2023-2024'),
('HK002', '2', '2023-2024'),
('HK003', '1', '2024-2025');

INSERT INTO LopHoc (malop, tenlop, makhoa)
VALUES
('LP001', 'Ngôn ngữ lập trình Java', 'MK001'),
('LP002', 'Quản Lý Dự Án','MK001'),
('LP003', 'Kiểm Chứng Phần Mềm','MK001');

INSERT INTO QuanLyHS (mssv, malop, mahk)
VALUES
('SV001', 'LP001', 'HK001'),
('SV002', 'LP001', 'HK001'),
('SV003', 'LP002', 'HK001'),
('SV004', 'LP002', 'HK001'),
('SV005', 'LP003', 'HK001'),
('SV006', 'LP003', 'HK001');

INSERT INTO QuanLyGV (msgv, malop, mahk)
VALUES
('GV001', 'LP001', 'HK001'),
('GV002', 'LP002', 'HK001'),
('GV003', 'LP003', 'HK001');

INSERT INTO ChuanDauRa (id, chuan)
VALUES
('G2.2', 'G2.2'),
('G3.1', 'G3.1'),
('G3.2', 'G3.2'),
('G6.1', 'G6.1');

INSERT INTO ThanhPhanDanhGia (id, thanhphan, tile)
VALUES
('A1', 'A1', 30),
('A2', 'A2', 0),
('A3', 'A3', 30),
('A4', 'A4', 40);

INSERT INTO QuanLyTPDG (thanhphan_id, chuan_id)
VALUES
('A1', 'G2.2'),
('A1', 'G3.1');

INSERT INTO QuanLyTPDG (thanhphan_id, chuan_id)
VALUES
('A3', 'G2.2'),
('A3', 'G3.1'),
('A3', 'G3.2');

INSERT INTO QuanLyTPDG (thanhphan_id, chuan_id)
VALUES
('A4', 'G2.2'),
('A4', 'G3.1'),
('A4', 'G3.2'),
('A4', 'G6.1');
