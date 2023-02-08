
CREATE TABLE `ipv4` (
  `fromip` int(11) UNSIGNED NOT NULL,
  `toip` int(11) UNSIGNED NOT NULL,
  `lon` double NOT NULL,
  `lat` double NOT NULL
) ;

CREATE TABLE `ipv6` (
  `fromip` bigint(20) UNSIGNED NOT NULL,
  `toip` bigint(20) UNSIGNED NOT NULL,
  `lon` double NOT NULL,
  `lat` double NOT NULL
) ;

ALTER TABLE `ipv4`
  ADD PRIMARY KEY (`fromip`),
  ADD KEY `toip` (`toip`);


ALTER TABLE `ipv6`
  ADD PRIMARY KEY (`fromip`),
  ADD KEY `toip` (`toip`);
COMMIT;
