-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2021-10-05 19:39:45
-- 服务器版本： 5.6.50-log
-- PHP 版本： 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `rotateCaptcha`
--

-- --------------------------------------------------------

--
-- 表的结构 `captchaImage`
--

CREATE TABLE `captchaImage` (
  `id` bigint(20) NOT NULL,
  `file` varchar(200) NOT NULL COMMENT '验证码图片的相对路径',
  `useNum` bigint(20) NOT NULL DEFAULT '0' COMMENT '使用次数',
  `yesNum` bigint(20) NOT NULL DEFAULT '0' COMMENT '成功次数',
  `addTime` bigint(10) NOT NULL DEFAULT '0',
  `imgMd5` varchar(32) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='验证码原图';

--
-- 转存表中的数据 `captchaImage`
--

INSERT INTO `captchaImage` (`id`, `file`, `useNum`, `yesNum`, `addTime`, `imgMd5`) VALUES
(6, 'file/captchaImage/20211003/6bfdde247a1bbf734bce313fb2119ad5.png', 1, 0, 1633269437, '0d891304abe618bb8d446f902f4074a3'),
(7, 'file/captchaImage/20211003/8dd428e558d3ae1618ac4ca465121f62.png', 1, 0, 1633269437, '2a0a0c9f8d93a08a5dfbd8bb0b014ff3'),
(8, 'file/captchaImage/20211003/80381788c90fc02a6d571cbd8b37a1e4.png', 1, 0, 1633269437, '72d858da2da97d75971a9116c9e963c5'),
(9, 'file/captchaImage/20211003/2d3992e30e09650eaf50c0c901596a2d.png', 1, 0, 1633269437, '957cad973fb2329dc66bed15eba66d73'),
(10, 'file/captchaImage/20211003/d39fb589d068165c802c387eb939b08c.png', 1, 0, 1633269437, '5422508f4c73d6b8054b1424c914cbca'),
(11, 'file/captchaImage/20211003/09bfde7dde58b096d0ca1541994e34d2.png', 1, 0, 1633269437, 'c704c0a2435ee7640fe4ed4390115db3'),
(12, 'file/captchaImage/20211003/e8aa52425aaae1ab0516a4b9507de5b2.png', 1, 0, 1633269437, '3ffc4683e3ff02a19d230cff3c53c449'),
(13, 'file/captchaImage/20211003/b37767dae7eac51739e00efb711e05c2.png', 1, 0, 1633269437, '784e5b9d3eb94ff4a9897b5eceea2e0f'),
(14, 'file/captchaImage/20211003/3ac5c70d4649a5f4f96a340941bcf547.png', 2, 0, 1633269437, '99582b5192168c48eb53723ba815727c'),
(15, 'file/captchaImage/20211003/6d7dff98dfde16036cc51022aa56d46a.png', 2, 0, 1633269437, '2597868d50f605a6653bbc094f1114bf'),
(16, 'file/captchaImage/20211003/e196dd7d691a0f109f09d98aa6f27fa2.png', 2, 0, 1633269437, 'ba29d01246e4a9a19f457f3abb5b63c9'),
(17, 'file/captchaImage/20211003/d510a7eb22b0e73a446826e4cf650d50.png', 2, 0, 1633269437, 'b34b7b71ac7aa71c797d1ea1a3eb4aa3'),
(18, 'file/captchaImage/20211003/84a5cb8bf0f3cbe3a171d02f0905d692.png', 2, 0, 1633269437, 'd19865d218198f77ae7a9333364b45fd'),
(19, 'file/captchaImage/20211003/86ac166dea1b976b6f534453d3e85632.png', 2, 0, 1633269437, 'ac2001e01c5d5fc2cac7973ac7b33e57'),
(20, 'file/captchaImage/20211003/f4a0ca97ee8361b647a0d1e57d1b89a1.png', 2, 0, 1633269437, '983bef7e4b59cafcd3f7a6a52f15cccd'),
(21, 'file/captchaImage/20211003/67f6b8207992f77ea729fc974af667c3.png', 2, 0, 1633269437, '5d6d253938bf4a0530744b3ca3f46568'),
(22, 'file/captchaImage/20211003/227ed09e198f53b9d02d1c2f621ab7d3.png', 2, 0, 1633269437, '7a9547a21a9e81c06f34f4bb5c0f052c'),
(23, 'file/captchaImage/20211003/7e2ac751213696c6976aedd96860931c.png', 2, 0, 1633269437, 'e3d099108041c726d7aaad19bf524285'),
(24, 'file/captchaImage/20211003/6cb904c4410a696d48bfbeab7f7c6faa.png', 2, 0, 1633269437, '35e0707a62a5e5783918a38cc280b49c'),
(25, 'file/captchaImage/20211003/aa2f3415f5d0aacd16d9c9576c8cbe94.png', 2, 0, 1633269437, 'db548abec16343a5e42167a0110eab55'),
(26, 'file/captchaImage/20211003/57e76cec2767246fa2b3bdc825825b96.png', 2, 0, 1633269437, '08aeb0934526b2875f710ef4914b411a'),
(27, 'file/captchaImage/20211003/b32471ce1b069ccdd6d7fd6b8f19543c.png', 2, 0, 1633269437, 'da090b56d744c3d34847b51e99a5acbf'),
(28, 'file/captchaImage/20211003/6c9deb6f96bc468ec91ca61747425661.png', 2, 0, 1633269437, 'b78ded37f77cb58729cf860e84e579fd'),
(29, 'file/captchaImage/20211003/ab0107ad0dd0e3f669b18b630d49c0c0.png', 2, 0, 1633269437, 'a696ca4244ee6bb0febb294f6c84e2bd'),
(30, 'file/captchaImage/20211003/3cb02627ffa206ce897f1af18b5ec48a.png', 2, 0, 1633269437, '25105277b3b666cf7fdba06c85741da8'),
(31, 'file/captchaImage/20211003/0b003516e950465923e2f0901acc5d9b.png', 2, 0, 1633269437, '61b3a2d3b5cf48d706aa296518496b75'),
(32, 'file/captchaImage/20211003/be20c0824120fd3c572dbd8e79e85e56.png', 2, 0, 1633269437, '8e9044668b795c6ea859f31109880356'),
(33, 'file/captchaImage/20211003/801a11c0013a9b436aea30ab92af2ccd.png', 2, 0, 1633269437, '5730357745408025167bf2a442153167'),
(34, 'file/captchaImage/20211003/458f530114f0abc57a2310ef4fbfebfc.png', 2, 0, 1633269437, '172b61a112f84c95ecc3b5f60ed8cda7'),
(35, 'file/captchaImage/20211003/dcb937860b94bf5456d61560b974d0cb.png', 2, 0, 1633269438, '96757184b9f3a0ed7cf9f166b1212042'),
(36, 'file/captchaImage/20211003/7b211c54b26d84d09302d2ce76e34892.png', 2, 0, 1633269438, '047d258bf1808a45d5bdfca9afe284d5'),
(37, 'file/captchaImage/20211003/eb19012e01ade6fc7aca76abc6d68b07.png', 2, 0, 1633269438, '47a0e6d056bd6840b89adddde29457fe'),
(38, 'file/captchaImage/20211003/148b2b7870dd180787c6b53017cb2a55.png', 2, 0, 1633269438, '429e229413c6761ffd66339a5439aede'),
(39, 'file/captchaImage/20211003/b6b4459d78674b60491ee93a3d7cb6bb.png', 2, 0, 1633269438, 'ad052f550e1e6250666080e016119ec2'),
(40, 'file/captchaImage/20211003/a98f3c24834f2eb88b5b19324bc437eb.png', 2, 0, 1633269438, '8852665d93dbf1da81e2870486a556d4'),
(41, 'file/captchaImage/20211003/af16bb321cf0dca471dc250e16fd8fb6.png', 2, 0, 1633269438, '5b2f5cb6610324766069ec17cb64c069'),
(42, 'file/captchaImage/20211003/d63383b7e0e1bad7e2cf2c410fa5cfe1.png', 2, 0, 1633269438, 'ebac03a7c7cb31dde7b1b7fd3385b999'),
(43, 'file/captchaImage/20211003/680f252da719aebba99c680de3f2cf28.png', 2, 0, 1633269438, 'ad4dabc7150e28d5cd0fc9ea83d3b6cd'),
(44, 'file/captchaImage/20211003/17f9bfbd74af113bff521d6be2f9da5d.png', 2, 0, 1633269438, 'aabfe91ea200db6647d5db4d58de4c6c'),
(45, 'file/captchaImage/20211003/55e93d9466f863b051b0ec708e082b07.png', 2, 0, 1633269438, 'd5bb024315b872ce12e4451e7998a2aa'),
(46, 'file/captchaImage/20211003/f3199eb84f452671601bbb14ca785faa.png', 2, 0, 1633269438, '9dbd111c96d344af0ae43724c8dfc80a'),
(47, 'file/captchaImage/20211003/a4cacce73bc915aa191f58ee4c38d757.png', 2, 0, 1633269438, '16ea4d883c757e817ab98df79586d7a0'),
(48, 'file/captchaImage/20211003/83f5302252c47992a025b44ede951844.png', 2, 0, 1633269438, '2c5e484904f045ff4fbd1269900fb7c8'),
(49, 'file/captchaImage/20211003/8f1b52e26aff8ca8de8b1e5db6572847.png', 2, 0, 1633269438, '3a903da2c4f768f2f9bff468d12af9b4'),
(50, 'file/captchaImage/20211003/4d35d755184df102a6d0c2ab528891e6.png', 2, 0, 1633269438, '23bdf3ec4e1767d845ffec4c21c6fecc'),
(51, 'file/captchaImage/20211003/a64d978a7d8aa9429c227b484abb8b71.png', 2, 0, 1633269438, '786663909c576949f4ff31b18e3bf8bb'),
(52, 'file/captchaImage/20211003/20f7b7adea197e1597811105e183b047.png', 2, 0, 1633269438, '3c9e3291197420b228ee1cbd99f3547a'),
(53, 'file/captchaImage/20211003/67e57d1f700f6289616f05e0d07927a1.png', 2, 0, 1633269438, '14f8b720f7f131dd5d8aa76b14846e11'),
(54, 'file/captchaImage/20211003/2333f18436b2b585c5cf80ab36a3e240.png', 2, 0, 1633269438, '702a46bc0d61540e4e0bb8da3e08beec'),
(55, 'file/captchaImage/20211003/5e71ea9c3fb89f9b090c7aacbd5d20d6.png', 2, 0, 1633269438, 'da99170977b8a1c1228ffdb097499a84'),
(56, 'file/captchaImage/20211003/936d272f592acb666c9112b8d2ca874c.png', 2, 0, 1633269438, '5228fcf8b3306017c2f51d66580c2003'),
(57, 'file/captchaImage/20211003/f8c59dd9ba830fe28534c85c821cfc3a.png', 2, 0, 1633269438, '1e863d5ce7ceccb9e8de7c886f2df3e5'),
(58, 'file/captchaImage/20211003/7dd0d723de233ef18b25edebeb417c4e.png', 2, 0, 1633269438, '353442fa3d8ed0e689b4bb0a034549f1'),
(59, 'file/captchaImage/20211003/e5915ac42ef687e0c56765fbc0f5b2d5.png', 2, 0, 1633269438, 'b0e955806eb25e21c68d4c1dfc7d4d6a'),
(60, 'file/captchaImage/20211003/0148be359c004d060f419401c4668bef.png', 2, 0, 1633269438, 'd123de850b84b7bce73888649e39aeca'),
(61, 'file/captchaImage/20211003/8432eefd8e0f35bf24fac6b23276e7b7.png', 2, 0, 1633269438, '81b39dc91eaf8672caa4f92441847f86'),
(62, 'file/captchaImage/20211003/c132c6f0c2f594791132ada83c8c87ed.png', 2, 0, 1633269438, '51c2686f999560e930b01a5eca5c640d'),
(63, 'file/captchaImage/20211003/11f50f25c426d32fe1f2beb9313622ce.png', 2, 0, 1633269438, '61b528701845bf0900dc9391cc2a8d39'),
(64, 'file/captchaImage/20211003/70ef0dbea60f3bc320ecbd561e98f2e1.png', 2, 0, 1633269438, '40bdce259932f311b761e8e0b235b8e7'),
(65, 'file/captchaImage/20211003/d79581b26e9fc8404c7b1367d30031de.png', 2, 0, 1633269438, '336cfcab9350c2ba37063ad951a13629'),
(66, 'file/captchaImage/20211003/73b54f28c525a8104f47c603c769b0b6.png', 2, 0, 1633269438, '1bb3424a1a8bed32b6e43be74c444d28'),
(67, 'file/captchaImage/20211003/5656ceb5c6b5ec8220517620bf1947dd.png', 2, 0, 1633269438, 'd25d0a717aea10deff4d947e3e4ccc32'),
(68, 'file/captchaImage/20211003/fcfd1db2390d51c750d8c2359de683f8.png', 2, 0, 1633269438, '9764d056330a0b27acb03154056c55f0'),
(69, 'file/captchaImage/20211003/c5f74ea8a275479a591535769a7a14da.png', 2, 0, 1633269438, '5a98949d26220de413106ea496a1cc03'),
(70, 'file/captchaImage/20211003/f65fc258fe04d7d935734e9dd53b9561.png', 2, 0, 1633269438, '51447e643bd874813202e28ea4687be7'),
(71, 'file/captchaImage/20211003/adee477f003389c20c99a7c7ae85f8c8.png', 2, 0, 1633269438, '20465692c16ae60f85a5abba0fad249c'),
(72, 'file/captchaImage/20211003/4f8c308128ad7e6965553729c3f5a968.png', 2, 0, 1633269438, '4ccea69498b1cdca6cc5913f7b238084'),
(73, 'file/captchaImage/20211003/b6b3936205bab90debcdcbba4913dd52.png', 2, 0, 1633269438, '76a090c9320c1531ae0732e361aa49da'),
(74, 'file/captchaImage/20211003/a9bf678fc83a46f76b5a1cfad1a81fdc.png', 2, 0, 1633269438, '7117f743afbb2f29deb84d30046c5b2e'),
(75, 'file/captchaImage/20211003/02a1b2198725450cc11131e8eae2c003.png', 2, 0, 1633269438, '71063976fe7d117629fca7a5bfbe58c4'),
(76, 'file/captchaImage/20211003/94004e28ec9e56cdb02cf5c4c21f6008.png', 2, 0, 1633269438, 'd05efbd6042cb2adbc6b1655a354174f'),
(77, 'file/captchaImage/20211003/a6eec2c69d8d8c44ea1da2c76c36e9d8.png', 2, 0, 1633269438, '52217a2924fdd37a6c93300309efd936'),
(78, 'file/captchaImage/20211003/3aceadede089a9e92070eb3063aec9bf.png', 2, 0, 1633269438, '9d270c505efbdda70b4437dc6e0fcda8'),
(79, 'file/captchaImage/20211003/2e34f38b8cfe4d202d1ed53128a8bf63.png', 2, 0, 1633269438, '3b0ed963511a9c356d715b3a10d3de9b');

-- --------------------------------------------------------

--
-- 表的结构 `captchaLog`
--

CREATE TABLE `captchaLog` (
  `id` bigint(20) NOT NULL,
  `captchaId` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '验证码图片ID',
  `rotationAngle` int(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '旋转角度',
  `addIp` varchar(15) NOT NULL DEFAULT '' COMMENT '添加ip',
  `tryNum` int(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '错误次数',
  `yesTime` bigint(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '验证成功日期',
  `yesUseTime` decimal(6,3) UNSIGNED NOT NULL DEFAULT '0.000' COMMENT '验证成功所用时间',
  `addTime` bigint(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '添加日期'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='验证码生成日志';

--
-- 转储表的索引
--

--
-- 表的索引 `captchaImage`
--
ALTER TABLE `captchaImage`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `captchaLog`
--
ALTER TABLE `captchaLog`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `captchaImage`
--
ALTER TABLE `captchaImage`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- 使用表AUTO_INCREMENT `captchaLog`
--
ALTER TABLE `captchaLog`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
