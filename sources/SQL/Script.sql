-- Table TECHNOLOGY

INSERT INTO `technology` (`technology_name`, `background_color`, `text_color`, `created_at`) VALUES
('JavaScript', '#f0db4d', '#323230', NOW()),
('HTML', '#e54c26', '#ffffff', NOW()),
('CSS', '#264de5', '#ffffff', NOW()),
('php', '#767ab3', '#000000', NOW()),
('MariaDB', '#ffffff', '#003444', NOW()),
('MySQL', '#ffffff', '#00748f', NOW()),
('Python', '#3670a2', '#ffce3b', NOW()),
('C++', '#649bd3', '#ffffff', NOW()),
('C#', '#390092', '#ffffff', NOW()),
('GO', '#ffffff', '#2cbdaf', NOW()),
('Scala', '#e83623', '#000000', NOW()),
('React', '#000000', '#61dafb', NOW()),
('VueJs', '#41b883', '#35495e', NOW()),
('Symfony', '#000000', '#ffffff', NOW()),
('NodeJs', '#8bc500', '#666861', NOW()),
('Ruby', '#d91404', '#ffffff', NOW()),
('Laravel', '#ff2c1f', '#ffffff', NOW());

-- Table CONTRACT

INSERT INTO `contract` (`name`, `created_at`) VALUES
('CDD',	NOW()),
('CDI',	NOW()),
('Freelance',	NOW()),
('Intérim',	NOW()),
('Alternance',	NOW()),
('Mission',	NOW()),
('Stage',	NOW()),
('Bénévolat',	NOW());

-- Table SALARY

INSERT INTO `salary` (`name`, `created_at`) VALUES
('moins de 20 000 €',	NOW()),
('20 000 - 30 000 €',	NOW()),
('30 000 - 45 000 €',	NOW()),
('45 000 - 60 000 €',	NOW()),
('plus de 60 000 €',	NOW()),
('Non déterminé - selon expérience',	NOW());

-- Table JOBTITLE

INSERT INTO `jobtitle` (`title`, `created_at`) VALUES
('Développeur web Frontend',	NOW()),
('Développeur web Backend',	NOW()),
('Développeur web Fullstack',	NOW());

-- Table EXPERIENCE

INSERT INTO `experience` (`years_number`, `created_at`) VALUES
('moins d''un an',	NOW()),
('de 1 à 2 ans',	NOW()),
('de 2 à 5 ans',	NOW()),
('de 5 à 10 ans',	NOW()),
('supérieur à 10 ans',	NOW());

-- Table SECTOR

INSERT INTO `sector` (`sector_name`, `created_at`) VALUES
('Agence',	NOW()),
('ESN',	NOW()),
('Remote',	NOW())

-- Table USER

INSERT INTO `user` (`email`, `password`, `roles`, `is_helped`, `created_at`) VALUES
('admin@admin.com',	'$2y$13$0Tw047NIBpVi4PJysBkkc.rk2oxC5KPJ6FQmCRAZeMdxcoB2FPrfm',	'[\"ROLE_ADMIN\"]', 1, NOW()); -- Admin2022!

-- Table CANDIDATE

-- INSERT INTO `candidate` (`adress_id`, `user_id`, `contract_id`, `experience_id`, `jobtitle_id`, `salary_id`, `last_name`, `first_name`, `birthday`, `genre`, `phone_number`, `picture`, `resume`, `description`, `position_held`, `portfolio`, `created_at`) VALUES
-- (1,	4,	2,	2,	1,	2,	'Bler',	'Sydney',	'1994-11-14',	1,	'+33434079677',	'https://randomuser.me/api/portraits/med/men/75.jpg',	'http://leblanc.fr/facilis-aspernatur-repellat-natus-inventore-aut-commodi-ullam',	'Minima eligendi sunt labore est unde velit. Incidunt quasi fugit dicta harum et.',	'Développeur JavaScript',	'http://www.techer.com/', NOW());

-- Table ADRESS

-- INSERT INTO `adress` (`street_number`, `street_name`, `zip`, `city`, `department`, `created_at`) VALUES
-- (4,	'rue du Fossé des Tanneurs',	'83000',	'Toulon',	'Var', NOW());

-- Table TECHOLOGY_CANDIDATE

-- INSERT INTO `technology_candidate` (`technology_id`, `candidate_id`) VALUES
-- ();

-- Table TECHOLOGY_JOB

-- INSERT INTO `technology_job` (`technology_id`, `job_id`) VALUES
-- ();

