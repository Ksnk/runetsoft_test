--
-- База данных: `runetsoft`
--

-- --------------------------------------------------------

--
-- Структура таблицы `rns_attr`
--

CREATE TABLE `rns_attr` (
  `id` int(11) NOT NULL,
  `brand` varchar(80) NOT NULL,
  `model` varchar(120) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `constr` varchar(10) NOT NULL,
  `diam` int(11) NOT NULL,
  `forceind` varchar(10) NOT NULL,
  `speedind` varchar(10) NOT NULL,
  `abbr` varchar(10) NOT NULL,
  `runflat` varchar(10) NOT NULL,
  `camera` varchar(10) NOT NULL,
  `season` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `rns_items`
--

CREATE TABLE `rns_items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `rns_attr`
--
ALTER TABLE `rns_attr`
  ADD UNIQUE KEY `id` (`id`);

--
-- Индексы таблицы `rns_items`
--
ALTER TABLE `rns_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `rns_items`
--
ALTER TABLE `rns_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;