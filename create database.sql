-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Авг 03 2022 г., 05:00
-- Версия сервера: 5.7.33
-- Версия PHP: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE river_task;
--
-- База данных: `river_task`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cargos`
--

CREATE TABLE `cargos` (
  `id_cargo` smallint(6) NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rus_name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `cargos`
--

INSERT INTO `cargos` (`id_cargo`, `name`, `rus_name`) VALUES
(1, 'Nothing', 'Ничего'),
(2, 'Wolf', 'Волк'),
(3, 'Goat', 'Коза'),
(4, 'Cabbage', 'Капуста');

-- --------------------------------------------------------

--
-- Структура таблицы `crossings`
--

CREATE TABLE `crossings` (
  `id_crossing` smallint(6) NOT NULL,
  `num_crossing` smallint(6) NOT NULL,
  `boat` smallint(11) NOT NULL,
  `is_right` tinyint(1) NOT NULL,
  `id_cargo` smallint(6) NOT NULL,
  `coast_1` tinyint(11) DEFAULT NULL,
  `coast_2` tinyint(11) DEFAULT NULL,
  `id_error` smallint(6) DEFAULT NULL,
  `num_coast` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`id_cargo`);

--
-- Индексы таблицы `crossings`
--
ALTER TABLE `crossings`
  ADD PRIMARY KEY (`id_crossing`),
  ADD KEY `boat` (`boat`),
  ADD KEY `id_cargo` (`id_cargo`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `cargos`
--
ALTER TABLE `cargos`
  MODIFY `id_cargo` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `crossings`
--
ALTER TABLE `crossings`
  MODIFY `id_crossing` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `crossings`
--
ALTER TABLE `crossings`
  ADD CONSTRAINT `crossings_ibfk_1` FOREIGN KEY (`boat`) REFERENCES `cargos` (`id_cargo`),
  ADD CONSTRAINT `crossings_ibfk_2` FOREIGN KEY (`id_cargo`) REFERENCES `cargos` (`id_cargo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
