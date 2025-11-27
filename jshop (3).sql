-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 27, 2025 lúc 05:24 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `jshop`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `image` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `image`) VALUES
(1, 'Nam giới', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRNrn8eStMxwAPkrBzdlA7P8WMLiuyOGHuaZw&s'),
(2, 'Nữ giới', 'https://cdnphoto.dantri.com.vn/X_FVjnLp3OQ9lzZZqrAVFiSPtEY=/thumb_w/1020/2023/10/22/trangsucnencotrongtudo6-1697987583665.jpg'),
(3, 'Trang sức cưới', 'https://www.cuoihoivietnam.com/upload_new/images/cach-chon-trang-suc-cuoi-9.jpg'),
(4, 'Phong thủy', 'https://ngoctham.com/wp-content/uploads/2021/11/01_6.jpg'),
(5, 'Đặc biệt', 'https://photo2.tinhte.vn/data/attachment-files/2024/11/8515370_tip_chon_giua_trang_suc_vang_bac.jpg'),
(6, 'Nhẫn Kim cương', 'https://i.pinimg.com/1200x/28/80/0d/28800d47a5cfccfaf0571693aa5db084.jpg'),
(7, 'Nhẫn Cưới', 'https://i.pinimg.com/1200x/7e/09/66/7e0966b95fbc40f7f8f1c899b0642c42.jpg'),
(8, 'Nhẫn Cầu hôn', 'https://i.pinimg.com/736x/18/d9/d8/18d9d8515f73a45d84500f5ee12d93e1.jpg'),
(9, 'Bông tai', 'https://i.pinimg.com/736x/73/c9/d9/73c9d9def2baf8bd04b9dbbd0a67ec91.jpg'),
(10, 'Dây chuyền Vàng', 'https://i.pinimg.com/1200x/a6/c5/ed/a6c5ed237babb1989107ce9371528e4f.jpg'),
(11, 'Đồng hồ Kim cương', 'https://i.pinimg.com/736x/7e/73/18/7e7318ba3eeb586d2f5cab3bdba8b0cd.jpg'),
(12, 'Trang sức mới', 'https://i.pinimg.com/1200x/e9/29/bb/e929bb497c8866f625fa840e3dd7ae6d.jpg'),
(13, 'Trang sức Nam', 'https://i.pinimg.com/1200x/b8/80/65/b88065a851c595d4b872ff4a9ec43355.jpg'),
(14, 'Trang sức Nữ', 'https://i.pinimg.com/1200x/ee/cf/23/eecf231b9a7658b3496da7980612aaca.jpg'),
(15, 'Trang sức May mắn', 'https://i.pinimg.com/736x/2f/59/a0/2f59a09cc8db53f68243912b421e607c.jpg'),
(16, 'Trang sức hợp mệnh', 'https://i.pinimg.com/1200x/6d/27/ad/6d27add3fd5e7c8fd5d32c498e3e51f0.jpg'),
(17, 'Trang sức theo cung hoàng đạo', 'https://i.pinimg.com/736x/71/7b/8d/717b8db127d6a6ba44e7d7fe5bf90df6.jpg'),
(18, 'Khác', 'https://down-vn.img.susercontent.com/file/sg-11134201-7qvd3-lf9hvh8hzn6093');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `collections`
--

CREATE TABLE `collections` (
  `collection_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `collections`
--

INSERT INTO `collections` (`collection_id`, `name`, `description`) VALUES
(1, 'Mùa xuân 2025', 'Bộ sưu tập trang sức mùa xuân'),
(2, 'Tình yêu vĩnh cửu', 'Dành cho cặp đôi'),
(3, 'Phong cách hiện đại', 'Thiết kế tối giản');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `fengshui`
--

CREATE TABLE `fengshui` (
  `fengshui_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `element` varchar(50) DEFAULT NULL,
  `zodiac` varchar(50) DEFAULT NULL,
  `birth_month` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `fengshui`
--

INSERT INTO `fengshui` (`fengshui_id`, `name`, `element`, `zodiac`, `birth_month`) VALUES
(1, 'Tỳ hưu', 'Kim', 'Tý', 1),
(2, 'Thiềm thừ', 'Thủy', 'Hợi', 12);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `guides`
--

CREATE TABLE `guides` (
  `guide_id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `materials`
--

CREATE TABLE `materials` (
  `material_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `materials`
--

INSERT INTO `materials` (`material_id`, `name`, `description`) VALUES
(1, 'Vàng 18K', 'Vàng 18K cao cấp'),
(2, 'Vàng trắng', 'Vàng trắng 18K'),
(3, 'Bạc 925', 'Bạc Ý 925'),
(4, 'Kim cương', 'Kim cương tự nhiên'),
(5, 'Ngọc trai', 'Ngọc trai nước ngọt');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `metal_prices`
--

CREATE TABLE `metal_prices` (
  `price_id` int(11) NOT NULL,
  `metal_name` varchar(50) DEFAULT NULL,
  `buy_price` decimal(15,2) DEFAULT NULL,
  `sell_price` decimal(15,2) DEFAULT NULL,
  `updated_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `news`
--

CREATE TABLE `news` (
  `news_id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `policies`
--

CREATE TABLE `policies` (
  `policy_id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `material_id` int(11) DEFAULT NULL,
  `collection_id` int(11) DEFAULT NULL,
  `purpose_id` int(11) DEFAULT NULL,
  `fengshui_id` int(11) DEFAULT NULL,
  `price` decimal(15,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL,
  `gender` enum('nam','nu','unisex') DEFAULT 'unisex',
  `material` varchar(50) DEFAULT NULL,
  `purpose` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `category_id`, `material_id`, `collection_id`, `purpose_id`, `fengshui_id`, `price`, `stock`, `created_at`, `image`, `gender`, `material`, `purpose`) VALUES
(1, 'Nhẫn cưới vàng 18K đôi', 'Thiết kế tinh tế, dành cho cặp đôi', 3, 1, 2, 3, NULL, 35000000.00, 10, '2025-11-04 16:05:52', NULL, 'unisex', NULL, NULL),
(2, 'Dây chuyền bạc 925 nữ', 'Phong cách trẻ trung, phù hợp hàng ngày', 2, 3, 3, 2, NULL, 2500000.00, 20, '2025-11-04 16:05:52', NULL, 'nu', NULL, NULL),
(3, 'Tỳ hưu vàng phong thủy', 'Mang lại tài lộc, may mắn', 4, 1, 1, 1, 1, 18000000.00, 5, '2025-11-04 16:05:52', NULL, 'unisex', NULL, NULL),
(4, 'Lắc tay kim cương nữ', 'Thiết kế sang trọng', 2, 4, 2, 1, NULL, 45000000.00, 3, '2025-11-04 16:05:52', NULL, 'unisex', NULL, NULL),
(5, 'Nhẫn nam titan đen', 'Mạnh mẽ, hiện đại', 1, 2, 3, 2, NULL, 12000000.00, 15, '2025-11-04 16:05:52', NULL, 'unisex', NULL, NULL),
(7, 'Nhẫn Bạc Đen Nam Tính', NULL, 1, NULL, NULL, NULL, NULL, 1800000.00, 10, '2025-11-26 21:35:17', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSJ2GePz2ar0PZLbLppY_kP7YRgUMZ6xvgnHg&s', 'nam', NULL, NULL),
(8, 'Vòng Tay Da Đính Bạc', NULL, 2, 3, NULL, NULL, NULL, 2100000.00, 10, '2025-11-26 21:35:17', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRmIzxf3NxGXrLhTadaBWFL-Ov2bH9ZYY3OIg&s', 'unisex', NULL, NULL),
(9, 'Dây Chuyền Nam Đính Đá Đen', NULL, 1, NULL, NULL, NULL, NULL, 2800000.00, 10, '2025-11-26 21:35:17', 'https://luxcreuni.com/wp-content/uploads/2024/10/day-chuyen-nam-den-kim-cuong-5-500x500.jpg', 'nam', NULL, NULL),
(10, 'Nhẫn Vàng Trắng Phong Cách', NULL, 1, NULL, NULL, NULL, NULL, 3500000.00, 10, '2025-11-26 21:35:17', 'https://tnj.vn/59810-large_default/nhan-nam-moissanite-don-gian-nnam0009.jpg', 'nam', NULL, NULL),
(11, 'Mặt Dây Chuyền Hổ Phách', NULL, 1, NULL, NULL, NULL, NULL, 4200000.00, 10, '2025-11-26 21:35:17', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQti3Ti7jMmCTpAS2b3AwxU6MIPYY9m-RFmNg&s', 'nam', NULL, NULL),
(12, 'Bông Tai Nam Bạc S925', NULL, 1, NULL, NULL, NULL, NULL, 1600000.00, 10, '2025-11-26 21:35:17', 'https://bizweb.dktcdn.net/100/487/604/products/z4430060620800-3cf1c06e70af1536012626d195627712.jpg?v=1690991544790', 'nam', NULL, NULL),
(13, 'Nhẫn Kim Cương Trắng', NULL, 2, NULL, NULL, NULL, NULL, 9800000.00, 0, '2025-11-26 21:44:47', 'https://locphuc.com.vn/Content/Images/022023/DSR0918BRW.WG01A/nhan-kim-cuong-DSR0918BRW-WG01A-g1.jpg', 'nu', NULL, NULL),
(14, 'Bông Tai Ngọc Trai Tự Nhiên', NULL, 2, NULL, NULL, NULL, NULL, 4600000.00, 0, '2025-11-26 21:44:47', 'https://quatangngoctrai.com/wp-content/uploads/2020/11/Bong-Tai-Ngoc-Trai-T20.027-1.jpg', 'nu', NULL, NULL),
(15, 'Dây Chuyền Trái Tim Vàng Hồng', NULL, 2, NULL, NULL, NULL, NULL, 5200000.00, 0, '2025-11-26 21:44:47', 'https://bizweb.dktcdn.net/100/461/213/products/vyn62-h-1696827181292.png?v=1751356900743', 'nu', NULL, NULL),
(16, 'Vòng Tay Bạc Nữ Tinh Tế', NULL, NULL, NULL, NULL, NULL, NULL, 2300000.00, 0, '2025-11-26 21:44:47', 'https://tnj.vn/40969-large_default/lac-tay-bac-nu-ban-to-khac-ten-ltn0212.jpg', 'unisex', NULL, NULL),
(17, 'Nhẫn Đính Đá Ruby Đỏ', NULL, 2, NULL, NULL, NULL, NULL, 7900000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/1200x/d5/9c/07/d59c07a8dc10beee4787561a0ebaba1d.jpg', 'nu', NULL, NULL),
(18, 'Bông Tai Vàng 18K ECZ', NULL, 2, NULL, NULL, NULL, NULL, 6700000.00, 0, '2025-11-26 21:44:47', 'https://apj.vn/wp-content/uploads/2020/10/BTP77-bong-tai-vang-vang-18k.jpg', 'nu', NULL, NULL),
(19, 'Nhẫn Vàng 24K Trơn', NULL, NULL, NULL, NULL, NULL, NULL, 7200000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/1200x/63/a8/30/63a8307328da322ebe978d188ea877a4.jpg', 'unisex', NULL, NULL),
(20, 'Dây Chuyền Vàng 18K', NULL, 2, NULL, NULL, NULL, NULL, 8500000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/1200x/6b/55/b6/6b55b61a7d8adadc35f42dbfb09e3a17.jpg', 'nu', NULL, NULL),
(21, 'Vòng Tay Vàng Hồng', NULL, 2, NULL, NULL, NULL, NULL, 9400000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/736x/1e/d3/95/1ed39537c1ab7c752976187d96020044.jpg', 'nu', NULL, NULL),
(22, 'Nhẫn Cưới Vàng 18K', NULL, 2, NULL, NULL, NULL, NULL, 12500000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/1200x/63/a8/30/63a8307328da322ebe978d188ea877a4.jpg', 'nu', NULL, NULL),
(23, 'Bông Tai Vàng 24K', NULL, 2, NULL, NULL, NULL, NULL, 10200000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/736x/20/cc/6a/20cc6ad8eafffb0a913cadcdff327c25.jpg', 'nu', NULL, NULL),
(24, 'Lắc Tay Vàng Ý', NULL, 2, NULL, NULL, NULL, NULL, 9800000.00, 0, '2025-11-26 21:44:47', 'https://cdn.pnj.io/images/detailed/113/gl0000z000158-lac-tay-vang-y-18k-pnj-01.png', 'nu', NULL, NULL),
(25, 'Trái tim anh đào - Sakura Heart', NULL, 2, NULL, NULL, NULL, NULL, 5000000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/736x/12/94/d8/1294d8f172c11d72b3871e9fc29173cb.jpg', 'nu', NULL, NULL),
(26, 'Giọt lệ trong - Tear of Purity', NULL, 2, NULL, NULL, NULL, NULL, 3500000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/1200x/21/19/d2/2119d25271416cf32575bd31a476fd1d.jpg', 'nu', NULL, NULL),
(27, 'Hồng Diễm - Crimson Radiance', NULL, 2, NULL, NULL, NULL, NULL, 2000000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/736x/db/55/0e/db550e92f30e34cfcce2b07d539fb356.jpg', 'nu', NULL, NULL),
(28, 'Bạch Liên – White Lotus', NULL, 2, NULL, NULL, NULL, NULL, 1800000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/736x/0b/05/27/0b0527600af58b7c721c7dd9095fcc94.jpg', 'nu', NULL, NULL),
(29, 'Băng Thanh - Pale Ocean', NULL, 2, NULL, NULL, NULL, NULL, 6000000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/1200x/2e/92/6d/2e926d90c7adee0ecb2e1ac56a7b48c5.jpg', 'nu', NULL, NULL),
(30, 'Ngọc Thẳm - Deep Sapphire', NULL, 2, NULL, NULL, NULL, NULL, 4200000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/736x/94/b7/06/94b7060d8100c3ff3978dc486f592bfd.jpg', 'nu', NULL, NULL),
(31, 'Hoàng Kim - Golden Radiance', NULL, 2, NULL, NULL, NULL, NULL, 3900000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/736x/19/f5/94/19f594022698131694fa3e22785445c8.jpg', 'nu', NULL, NULL),
(32, 'Nhẫn Ngọc Trai Tinh Khiết', NULL, 2, NULL, NULL, NULL, NULL, 2800000.00, 0, '2025-11-26 21:44:47', 'https://trangsucvn.com/images/201410/goods_img/7727_P_1414469171678.jpg', 'nu', NULL, NULL),
(33, 'Vòng Tay Kim Cương Mini', NULL, 2, NULL, NULL, NULL, NULL, 4200000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/1200x/19/f5/94/19f594022698131694fa3e22785445c8.jpg', 'nu', NULL, NULL),
(34, 'Bông Tai Vàng Trắng ECZ', NULL, 2, NULL, NULL, NULL, NULL, 3500000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/736x/0b/39/e5/0b39e5c9b0b2cf5922e7412b83f9450b.jpg', 'nu', NULL, NULL),
(35, 'Dây Chuyền Ngọc Lục Bảo', NULL, 2, NULL, NULL, NULL, NULL, 3900000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/1200x/bd/73/17/bd73176f7429f22ec13182f48bc57f8f.jpg', 'nu', NULL, NULL),
(36, 'Dây Chuyền Ruby Hồng', NULL, 2, NULL, NULL, NULL, NULL, 9900000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/736x/f0/a0/1b/f0a01b76c3822da238cade1b7a02fc74.jpg', 'nu', NULL, NULL),
(37, 'Bông Tai Kim Cương Tự Nhiên', NULL, 2, NULL, NULL, NULL, NULL, 3900000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/1200x/2e/7d/1a/2e7d1aec9594cbc101de0cb06f3a008f.jpg', 'nu', NULL, NULL),
(38, 'Hộp Đựng Trang Sức Cao Cấp', NULL, 2, NULL, NULL, NULL, NULL, 650000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/1200x/53/81/da/5381da261d5d0a21e66d9c2a16d24181.jpg', 'nu', NULL, NULL),
(39, 'Khăn Lau Trang Sức Chuyên Dụng', NULL, NULL, NULL, NULL, NULL, NULL, 12000.00, 0, '2025-11-26 21:44:47', 'https://salt.tikicdn.com/cache/750x750/ts/product/0a/87/5a/e3fd6931b261683d3fe92dbb86da3865.jpg', 'unisex', NULL, NULL),
(40, 'Dụng Cụ Vệ Sinh Trang Sức', NULL, NULL, NULL, NULL, NULL, NULL, 180000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/736x/7b/ee/cd/7beecd9db2fe247eae188fcdce96603b.jpg', 'unisex', NULL, NULL),
(41, 'Găng Tay Nữ Trang', NULL, 2, NULL, NULL, NULL, NULL, 95000.00, 0, '2025-11-26 21:44:47', 'https://down-vn.img.susercontent.com/file/vn-11134202-7ras8-m0er46ug0i3309', 'nu', NULL, NULL),
(42, 'Dây Chuyền Ngọc Trai Tự Nhiên', NULL, 2, NULL, NULL, NULL, NULL, 5900000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/736x/6b/65/87/6b658756a5d0d507a4bf95903a0458cf.jpg', 'nu', NULL, NULL),
(43, 'Bông Tai Ngọc Bích', NULL, 2, NULL, NULL, NULL, NULL, 6200000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/1200x/0a/8c/3e/0a8c3ea16b900e352177db8658ed944d.jpg', 'nu', NULL, NULL),
(44, 'Vòng Tay Ngọc Jade', NULL, 2, NULL, NULL, NULL, NULL, 7200000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/1200x/05/0d/3a/050d3a6f049dceab7d67cc5d3867967d.jpg', 'nu', NULL, NULL),
(45, 'Nhẫn Ngọc Lục Bảo', NULL, 2, NULL, NULL, NULL, NULL, 8800000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/1200x/20/32/07/203207c926fb2907d10e0c928e6f0487.jpg', 'nu', NULL, NULL),
(46, 'Chuỗi Ngọc Trai Hồng', NULL, 2, NULL, NULL, NULL, NULL, 9200000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/1200x/56/ce/bb/56cebb73ab3a971991e6e235a45e15e8.jpg', 'nu', NULL, NULL),
(47, 'Dây Chuyền Ngọc Lam', NULL, 2, NULL, NULL, NULL, NULL, 6800000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/1200x/51/7c/ae/517cae45d592f9b8a8cdd78d2226c2a1.jpg', 'nu', NULL, NULL),
(48, 'Nhẫn Kim Cương Tự Nhiên', NULL, 2, NULL, NULL, NULL, NULL, 26500000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/1200x/0c/dc/37/0cdc37daccd6d99c494d6a4d65dd8365.jpg', 'nu', NULL, NULL),
(49, 'Bông Tai Kim Cương ECZ', NULL, 2, NULL, NULL, NULL, NULL, 18900000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/736x/15/c2/9d/15c29d606a706aa0f83d05082894bf6e.jpg', 'nu', NULL, NULL),
(50, 'Vòng Tay Kim Cương Trắng', NULL, 2, NULL, NULL, NULL, NULL, 31200000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/1200x/21/11/16/211116e6dfde6ee4dcfab64c1abce9ad.jpg', 'nu', NULL, NULL),
(51, 'Dây Chuyền Kim Cương Hồng', NULL, 2, NULL, NULL, NULL, NULL, 28800000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/1200x/d2/95/85/d2958516b3492bf5c90afd2cd6696deb.jpg', 'nu', NULL, NULL),
(52, 'Nhẫn Đôi Kim Cương Vàng', NULL, 2, NULL, NULL, NULL, NULL, 35600000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/736x/70/70/c9/7070c928554920bb6e23d48225b74c6a.jpg', 'nu', NULL, NULL),
(53, 'Mặt Dây Chuyền Kim Cương Đen', NULL, 2, NULL, NULL, NULL, NULL, 21000000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/1200x/99/b9/4b/99b94b812161b54dc18009425bb7dbe5.jpg', 'nu', NULL, NULL),
(54, 'Đồng Hồ Nam Titanium', NULL, 1, NULL, NULL, NULL, NULL, 5200000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/1200x/01/c5/dc/01c5dc48a095ca19fa48706a8bcec208.jpg', 'nam', NULL, NULL),
(55, 'Đồng Hồ Nữ Vàng Hồng', NULL, 2, NULL, NULL, NULL, NULL, 7800000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/736x/96/9a/94/969a94b870c6f62f622fd8451c6c69e2.jpg', 'nu', NULL, NULL),
(56, 'Đồng Hồ Cặp Đôi Classic', NULL, NULL, NULL, NULL, NULL, NULL, 8900000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/1200x/ff/73/04/ff73047011707b69fa8811fec4887134.jpg', 'unisex', NULL, NULL),
(57, 'Đồng Hồ Mạ Bạc Sang Trọng', NULL, 1, NULL, NULL, NULL, NULL, 6500000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/1200x/68/55/07/685507e770cdd30c4fa950545ad03866.jpg', 'nam', NULL, NULL),
(58, 'Đồng Hồ Kim Cương Đen', NULL, 2, NULL, NULL, NULL, NULL, 12500000.00, 0, '2025-11-26 21:44:47', 'https://chicwatchluxury.vn/wp-content/uploads/2024/08/84da2530c28466da3f9531-scaled.jpg', 'nu', NULL, NULL),
(59, 'Đồng Hồ Quartz Thời Trang', NULL, 2, NULL, NULL, NULL, NULL, 4500000.00, 0, '2025-11-26 21:44:47', 'https://winwatch.vn/wp-content/uploads/2024/11/Orient-5.webp', 'nu', NULL, NULL),
(60, 'Đồng Hồ Mặt Vuông Retro', NULL, NULL, NULL, NULL, NULL, NULL, 5200000.00, 0, '2025-11-26 21:44:47', 'https://donghoduyanh.com/images/news/2020/01/13/large/ca-tinh-cung-dong-ho-mat-vuong.jpg', 'unisex', NULL, NULL),
(61, 'Nhẫn Đá Ruby Đỏ', NULL, 2, NULL, NULL, NULL, NULL, 8900000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/1200x/a7/e0/b2/a7e0b26ac09ab9e542aacfb2d89e70da.jpg', 'nu', NULL, NULL),
(62, 'Vòng Tay Đá Sapphire', NULL, NULL, NULL, NULL, NULL, NULL, 9700000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/1200x/a5/4a/a8/a54aa84b72a7beca992ede9f4d651488.jpg', 'unisex', NULL, NULL),
(63, 'Bông Tai Đá Emerald', NULL, 2, NULL, NULL, NULL, NULL, 11500000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/736x/a0/af/b4/a0afb494e66c9d436e43f29ebe2d3a9e.jpg', 'nu', NULL, NULL),
(64, 'Dây Chuyền Đá Topaz Xanh', NULL, 2, NULL, NULL, NULL, NULL, 9400000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/1200x/0a/79/2e/0a792edf223a5393ed5a9fce73b3167a.jpg', 'nu', NULL, NULL),
(65, 'Lắc Tay Đá Garnet', NULL, 2, NULL, NULL, NULL, NULL, 8800000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/736x/d3/47/66/d34766167bbf54ad3a42672b71868e9e.jpg', 'nu', NULL, NULL),
(66, 'Nhẫn Đá Peridot', NULL, 2, NULL, NULL, NULL, NULL, 9200000.00, 0, '2025-11-26 21:44:47', 'https://i.pinimg.com/736x/50/17/35/501735d1f4db676c479a3b5176f84930.jpg', 'nu', NULL, NULL),
(67, 'Nhẫn Bạc S925', NULL, 2, NULL, NULL, NULL, NULL, 950000.00, 0, '2025-11-26 21:44:47', 'https://bsj.vn/wp-content/uploads/2017/09/nhan-bac-nu-bac-ta-cao-cap-bac-bsj-13-768x768.jpg', 'nu', NULL, NULL),
(68, 'Vòng Tay Bạc Đính Đá', NULL, 2, NULL, NULL, NULL, NULL, 1250000.00, 0, '2025-11-26 21:44:47', 'https://calliesilver.com/wp-content/uploads/2022/07/Vo%CC%80ng-tay-ba%CC%A3c-Callie-Silver-di%CC%81nh-full-da%CC%81-VT21.3-768x768.jpeg', 'nu', NULL, NULL),
(69, 'Bông Tai Bạc Trắng', NULL, 2, NULL, NULL, NULL, NULL, 870000.00, 0, '2025-11-26 21:44:47', 'https://bizweb.dktcdn.net/100/461/213/products/vye28-t-b.png?v=1671245465853', 'nu', NULL, NULL),
(70, 'Dây Chuyền Bạc Ý', NULL, 2, NULL, NULL, NULL, NULL, 1500000.00, 0, '2025-11-26 21:44:47', 'https://bizweb.dktcdn.net/thumb/large/100/302/551/products/ne-ncropstudio-session-038-1.jpg?v=1759388474590', 'nu', NULL, NULL),
(71, 'Lắc Tay Bạc Mix Vàng', NULL, 2, NULL, NULL, NULL, NULL, 1350000.00, 0, '2025-11-26 21:44:47', 'https://pos.nvncdn.com/331316-3334/ps/20250125_1JF23tmh2H.jpeg?v=1737813526', 'nu', NULL, NULL),
(72, 'Mặt Dây Chuyền Bạc', NULL, 1, NULL, NULL, NULL, NULL, 1100000.00, 0, '2025-11-26 21:44:47', 'https://tnj.vn/img/cms/day-chuyen-nam-bac/DCK0028/day-chuyen-bac-y-nam-DCK0028-3.jpg', 'nam', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `purposes`
--

CREATE TABLE `purposes` (
  `purpose_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `purposes`
--

INSERT INTO `purposes` (`purpose_id`, `name`) VALUES
(1, 'Quà tặng'),
(2, 'Trang sức hàng ngày'),
(3, 'Cưới hỏi');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','employee','admin') DEFAULT 'customer',
  `full_name` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`user_id`, `email`, `phone`, `password`, `role`, `full_name`, `address`, `created_at`) VALUES
(1, 'admin@gmail.com', '0901234567', '$2y$10$W6x3yK8z9v0q1r2t3u4v5e6w7x8y9z0a1b2c3d4e5f6g7h8i9j0k1l', 'admin', 'Quản trị viên', 'Hà Nội', '2025-11-04 16:05:52'),
(2, 'employee@gmail.com', '0907654321', '$2y$10$W6x3yK8z9v0q1r2t3u4v5e6w7x8y9z0a1b2c3d4e5f6g7h8i9j0k1l', 'employee', 'Nhân viên bán hàng', 'TP.HCM', '2025-11-04 16:05:52'),
(3, 'customer@gmail.com', '0912345678', '$2y$10$W6x3yK8z9v0q1r2t3u4v5e6w7x8y9z0a1b2c3d4e5f6g7h8i9j0k1l', 'customer', 'Khách hàng', 'Đà Nẵng', '2025-11-04 16:05:52');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Chỉ mục cho bảng `collections`
--
ALTER TABLE `collections`
  ADD PRIMARY KEY (`collection_id`);

--
-- Chỉ mục cho bảng `fengshui`
--
ALTER TABLE `fengshui`
  ADD PRIMARY KEY (`fengshui_id`);

--
-- Chỉ mục cho bảng `guides`
--
ALTER TABLE `guides`
  ADD PRIMARY KEY (`guide_id`);

--
-- Chỉ mục cho bảng `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`material_id`);

--
-- Chỉ mục cho bảng `metal_prices`
--
ALTER TABLE `metal_prices`
  ADD PRIMARY KEY (`price_id`);

--
-- Chỉ mục cho bảng `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`news_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `policies`
--
ALTER TABLE `policies`
  ADD PRIMARY KEY (`policy_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `fk_category` (`category_id`),
  ADD KEY `fk_material` (`material_id`),
  ADD KEY `fk_collection` (`collection_id`),
  ADD KEY `fk_purpose` (`purpose_id`),
  ADD KEY `fk_fengshui` (`fengshui_id`);

--
-- Chỉ mục cho bảng `purposes`
--
ALTER TABLE `purposes`
  ADD PRIMARY KEY (`purpose_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `collections`
--
ALTER TABLE `collections`
  MODIFY `collection_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `fengshui`
--
ALTER TABLE `fengshui`
  MODIFY `fengshui_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `materials`
--
ALTER TABLE `materials`
  MODIFY `material_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT cho bảng `purposes`
--
ALTER TABLE `purposes`
  MODIFY `purpose_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_collection` FOREIGN KEY (`collection_id`) REFERENCES `collections` (`collection_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_fengshui` FOREIGN KEY (`fengshui_id`) REFERENCES `fengshui` (`fengshui_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_material` FOREIGN KEY (`material_id`) REFERENCES `materials` (`material_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_purpose` FOREIGN KEY (`purpose_id`) REFERENCES `purposes` (`purpose_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
