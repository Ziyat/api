-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Сен 08 2018 г., 10:26
-- Версия сервера: 5.6.37
-- Версия PHP: 7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `watch_test`
--

-- --------------------------------------------------------

--
-- Структура таблицы `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(10) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=231 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `countries`
--

INSERT INTO `countries` (`id`, `name`, `code`) VALUES
(1, 'Andorra', 'ad'),
(2, 'United Arab Emirates', 'ae'),
(3, 'Afghanistan', 'af'),
(4, 'Antigua and Barbuda', 'ag'),
(5, 'Anguilla', 'ai'),
(6, 'Albania', 'al'),
(7, 'Armenia', 'am'),
(8, 'Netherlands Antilles', 'an'),
(9, 'Angola', 'ao'),
(10, 'Argentina', 'ar'),
(11, 'Austria', 'at'),
(12, 'Australia', 'au'),
(13, 'Aruba', 'aw'),
(14, 'Azerbaijan', 'az'),
(15, 'Bosnia and Herzegovina', 'ba'),
(16, 'Barbados', 'bb'),
(17, 'Bangladesh', 'bd'),
(18, 'Belgium', 'be'),
(19, 'Burkina Faso', 'bf'),
(20, 'Bulgaria', 'bg'),
(21, 'Bahrain', 'bh'),
(22, 'Burundi', 'bi'),
(23, 'Benin', 'bj'),
(24, 'Bermuda', 'bm'),
(25, 'Brunei Darussalam', 'bn'),
(26, 'Bolivia', 'bo'),
(27, 'Brazil', 'br'),
(28, 'Bahamas', 'bs'),
(29, 'Bhutan', 'bt'),
(30, 'Botswana', 'bw'),
(31, 'Belarus', 'by'),
(32, 'Belize', 'bz'),
(33, 'Canada', 'ca'),
(34, 'Cocos (Keeling) Islands', 'cc'),
(35, 'Democratic Republic of the Congo', 'cd'),
(36, 'Central African Republic', 'cf'),
(37, 'Congo', 'cg'),
(38, 'Switzerland', 'ch'),
(39, 'Cote D''Ivoire (Ivory Coast)', 'ci'),
(40, 'Cook Islands', 'ck'),
(41, 'Chile', 'cl'),
(42, 'Cameroon', 'cm'),
(43, 'China', 'cn'),
(44, 'Colombia', 'co'),
(45, 'Costa Rica', 'cr'),
(46, 'Cuba', 'cu'),
(47, 'Cape Verde', 'cv'),
(48, 'Christmas Island', 'cx'),
(49, 'Cyprus', 'cy'),
(50, 'Czech Republic', 'cz'),
(51, 'Germany', 'de'),
(52, 'Djibouti', 'dj'),
(53, 'Denmark', 'dk'),
(54, 'Dominica', 'dm'),
(55, 'Dominican Republic', 'do'),
(56, 'Algeria', 'dz'),
(57, 'Ecuador', 'ec'),
(58, 'Estonia', 'ee'),
(59, 'Egypt', 'eg'),
(60, 'Western Sahara', 'eh'),
(61, 'Eritrea', 'er'),
(62, 'Spain', 'es'),
(63, 'Ethiopia', 'et'),
(64, 'Finland', 'fi'),
(65, 'Fiji', 'fj'),
(66, 'Falkland Islands (Malvinas)', 'fk'),
(67, 'Federated States of Micronesia', 'fm'),
(68, 'Faroe Islands', 'fo'),
(69, 'France', 'fr'),
(70, 'Gabon', 'ga'),
(71, 'Great Britain (UK)', 'gb'),
(72, 'Grenada', 'gd'),
(73, 'Georgia', 'ge'),
(74, 'French Guiana', 'gf'),
(75, 'NULL', 'gg'),
(76, 'Ghana', 'gh'),
(77, 'Gibraltar', 'gi'),
(78, 'Greenland', 'gl'),
(79, 'Gambia', 'gm'),
(80, 'Guinea', 'gn'),
(81, 'Guadeloupe', 'gp'),
(82, 'Equatorial Guinea', 'gq'),
(83, 'Greece', 'gr'),
(84, 'S. Georgia and S. Sandwich Islands', 'gs'),
(85, 'Guatemala', 'gt'),
(86, 'Guinea-Bissau', 'gw'),
(87, 'Guyana', 'gy'),
(88, 'Hong Kong', 'hk'),
(89, 'Honduras', 'hn'),
(90, 'Croatia (Hrvatska)', 'hr'),
(91, 'Haiti', 'ht'),
(92, 'Hungary', 'hu'),
(93, 'Indonesia', 'id'),
(94, 'Ireland', 'ie'),
(95, 'Israel', 'il'),
(96, 'India', 'in'),
(97, 'Iraq', 'iq'),
(98, 'Iran', 'ir'),
(99, 'Iceland', 'is'),
(100, 'Italy', 'it'),
(101, 'Jamaica', 'jm'),
(102, 'Jordan', 'jo'),
(103, 'Japan', 'jp'),
(104, 'Kenya', 'ke'),
(105, 'Kyrgyzstan', 'kg'),
(106, 'Cambodia', 'kh'),
(107, 'Kiribati', 'ki'),
(108, 'Comoros', 'km'),
(109, 'Saint Kitts and Nevis', 'kn'),
(110, 'Korea (North)', 'kp'),
(111, 'Korea (South)', 'kr'),
(112, 'Kuwait', 'kw'),
(113, 'Cayman Islands', 'ky'),
(114, 'Kazakhstan', 'kz'),
(115, 'Laos', 'la'),
(116, 'Lebanon', 'lb'),
(117, 'Saint Lucia', 'lc'),
(118, 'Liechtenstein', 'li'),
(119, 'Sri Lanka', 'lk'),
(120, 'Liberia', 'lr'),
(121, 'Lesotho', 'ls'),
(122, 'Lithuania', 'lt'),
(123, 'Luxembourg', 'lu'),
(124, 'Latvia', 'lv'),
(125, 'Libya', 'ly'),
(126, 'Morocco', 'ma'),
(127, 'Monaco', 'mc'),
(128, 'Moldova', 'md'),
(129, 'Madagascar', 'mg'),
(130, 'Marshall Islands', 'mh'),
(131, 'Macedonia', 'mk'),
(132, 'Mali', 'ml'),
(133, 'Myanmar', 'mm'),
(134, 'Mongolia', 'mn'),
(135, 'Macao', 'mo'),
(136, 'Northern Mariana Islands', 'mp'),
(137, 'Martinique', 'mq'),
(138, 'Mauritania', 'mr'),
(139, 'Montserrat', 'ms'),
(140, 'Malta', 'mt'),
(141, 'Mauritius', 'mu'),
(142, 'Maldives', 'mv'),
(143, 'Malawi', 'mw'),
(144, 'Mexico', 'mx'),
(145, 'Malaysia', 'my'),
(146, 'Mozambique', 'mz'),
(147, 'Namibia', 'na'),
(148, 'New Caledonia', 'nc'),
(149, 'Niger', 'ne'),
(150, 'Norfolk Island', 'nf'),
(151, 'Nigeria', 'ng'),
(152, 'Nicaragua', 'ni'),
(153, 'Netherlands', 'nl'),
(154, 'Norway', 'no'),
(155, 'Nepal', 'np'),
(156, 'Nauru', 'nr'),
(157, 'Niue', 'nu'),
(158, 'New Zealand (Aotearoa)', 'nz'),
(159, 'Oman', 'om'),
(160, 'Panama', 'pa'),
(161, 'Peru', 'pe'),
(162, 'French Polynesia', 'pf'),
(163, 'Papua New Guinea', 'pg'),
(164, 'Philippines', 'ph'),
(165, 'Pakistan', 'pk'),
(166, 'Poland', 'pl'),
(167, 'Saint Pierre and Miquelon', 'pm'),
(168, 'Pitcairn', 'pn'),
(169, 'Palestinian Territory', 'ps'),
(170, 'Portugal', 'pt'),
(171, 'Palau', 'pw'),
(172, 'Paraguay', 'py'),
(173, 'Qatar', 'qa'),
(174, 'Reunion', 're'),
(175, 'Romania', 'ro'),
(176, 'Russian Federation', 'ru'),
(177, 'Rwanda', 'rw'),
(178, 'Saudi Arabia', 'sa'),
(179, 'Solomon Islands', 'sb'),
(180, 'Seychelles', 'sc'),
(181, 'Sudan', 'sd'),
(182, 'Sweden', 'se'),
(183, 'Singapore', 'sg'),
(184, 'Saint Helena', 'sh'),
(185, 'Slovenia', 'si'),
(186, 'Svalbard and Jan Mayen', 'sj'),
(187, 'Slovakia', 'sk'),
(188, 'Sierra Leone', 'sl'),
(189, 'San Marino', 'sm'),
(190, 'Senegal', 'sn'),
(191, 'Somalia', 'so'),
(192, 'Suriname', 'sr'),
(193, 'Sao Tome and Principe', 'st'),
(194, 'El Salvador', 'sv'),
(195, 'Syria', 'sy'),
(196, 'Swaziland', 'sz'),
(197, 'Turks and Caicos Islands', 'tc'),
(198, 'Chad', 'td'),
(199, 'French Southern Territories', 'tf'),
(200, 'Togo', 'tg'),
(201, 'Thailand', 'th'),
(202, 'Tajikistan', 'tj'),
(203, 'Tokelau', 'tk'),
(204, 'Turkmenistan', 'tm'),
(205, 'Tunisia', 'tn'),
(206, 'Tonga', 'to'),
(207, 'Turkey', 'tr'),
(208, 'Trinidad and Tobago', 'tt'),
(209, 'Tuvalu', 'tv'),
(210, 'Taiwan', 'tw'),
(211, 'Tanzania', 'tz'),
(212, 'Ukraine', 'ua'),
(213, 'Uganda', 'ug'),
(214, 'Uruguay', 'uy'),
(215, 'Uzbekistan', 'uz'),
(216, 'Saint Vincent and the Grenadines', 'vc'),
(217, 'Venezuela', 've'),
(218, 'Virgin Islands (British)', 'vg'),
(219, 'Virgin Islands (U.S.)', 'vi'),
(220, 'Viet Nam', 'vn'),
(221, 'Vanuatu', 'vu'),
(222, 'Wallis and Futuna', 'wf'),
(223, 'Samoa', 'ws'),
(224, 'Yemen', 'ye'),
(225, 'Mayotte', 'yt'),
(226, 'South Africa', 'za'),
(227, 'Zambia', 'zm'),
(228, 'Zaire (former)', 'zr'),
(229, 'Zimbabwe', 'zw'),
(230, 'United States of America', 'us');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=231;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
