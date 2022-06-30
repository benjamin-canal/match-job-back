# Liste des routes

## Routes de l'application

| URL                 | Méthode HTTP | Titre                  | Contenu                   |
| ------------------- | ------------ | ---------------------- | ------------------------- |
| `/`                 | `GET`        | `Match-job Accueil`    |                           |
| `/`                 | `Post`       | `connexion`            | ------------------------- |
| `/`                 | `Post`       | `subscribe`            | ------------------------- |
| `/mentions-legales` | `GET`        | `Mentions-legals`      | legal mention             |
| `/a-propos`         | `GET`        | `A propos`             | read-more                 |
| `/avis`             | `feedback`   | `Retour d'expériences` | share your feedback       |
| `/fil`              | `GET`        | `feed`                 |                           |
| `/profil`           | `GET`        | `profile`              | personal area             |
| `/profil/edition`   | `PATCH`      | `edit`                 | change your profile       |

## Routes BackOffice

| URL                          | Méthode HTTP | Titre                                   | Contenu                                            |
| ---------------------------- | ------------ | --------------------------------------- | -------------------------------------------------- |
| `/`                          | `GET`        | `Backoffice Match Job`                  | `Backoffice dashboard`                             |
| `/back/contract`             | `GET`        | `Liste des types de contrats`           | `Contracts list`                                   |
| `/back/contract/new`         | `POST`       | `Ajout d'un type de contrat`            | `Form to add a contract`                           |
| `/back/contract/{id}/edit`   | `PUT`        | `Modification d'un type de contrat`     | `Form to update a contract`                        |
| `/back/contract/{id}`        | `GET`        | `Informations d'un type de contrat`     | `displays the informations about contract`         |
| `/back/contract/{id}`        | `POST`       | `Suppression d'un type de contrat`      | `message before remove a contract`                 |
| `/back/experience`           | `GET`        | `liste des types d'expériences`         | `experiences list`                                 |
| `/back/experience/new`       | `POST`       | `Ajout d'un type d'expérience`          | `Form to add a experience`                         |
| `/back/experience/{id}/edit` | `PUT`        | `Modification d'un type d'expérience`   | `Form to update a experience`                      |
| `/back/experience/{id}`      | `GET`        | `Informations d'un type d'expérience`   | `displays the informations about a experience`     |
| `/back/experience/{id}`      | `POST`       | `Suppression d'un type d'expérience`    | `message before remove a experience`               |
| `/back/jobtitle`             | `GET`        | `liste des intitulés d'emplois`         | `jobtitles list`                                   |
| `/back/jobtitle/new`         | `POST`       | `Ajout d'un intitulé d'emploi`          | `Form to add a jobtitle`                           |
| `/back/jobtitle/{id}/edit`   | `PUT`        | `Modification d'un intitulé d'emploi`   | `Form to update a jobtitle`                        |
| `/back/jobtitle/{id}`        | `GET`        | `Informations d'un intitulé d'emploi`   | `displays the informations about a jobtitle`       |
| `/back/jobtitle/{id}`        | `POST`       | `Suppression d'un intitulé d'emploi`    | `message before remove a jobtitle`                 |
| `/back/salary`               | `GET`        | `Liste des tranches de salaires`        | `Salary-brackets list`                             |
| `/back/salary/new`           | `POST`       | `Ajout d'une tranche de salaire`        | `Form to add a salary-bracket`                     |
| `/back/salary/{id}/edit`     | `PUT`        | `Modification d'une tranche de salaire` | `Form to update a salary-bracket`                  |
| `/back/salary/{id}`          | `GET`        | `Informations d'une tranche de salaire` | `displays the informations about a salary-bracket` |
| `/back/salary/{id}`          | `POST`       | `Suppression d'une tranche de salaire`  | `message before remove a salary-bracket`           |
| `/back/sector`               | `GET`        | `liste des secteurs d'activités`        | `Sectors list`                                     |
| `/back/sector/new`           | `POST`       | `Ajout d'un secteur d'activité`         | `Form to add a sector`                             |
| `/back/sector/{id}/edit`     | `PUT`        | `Modification d'un secteur d'activité`  | `Form to update a sector`                          |
| `/back/sector/{id}`          | `GET`        | `Informations d'un secteur d'activité`  | `displays the informations about a sector`         |
| `/back/sector/{id}`          | `POST`       | `Suppression d'un secteur d'activité`   | `message before remove a sector`                   |
| `/back/technology`           | `GET`        | `Liste des technologies`                | `Technologies list`                                |
| `/back/technology/new`       | `POST`       | `Ajout d'une technologie`               | `Form to add a technology`                         |
| `/back/technology/{id}/edit` | `PUT`        | `Modification d'une technologie`        | `Form to update a technology`                      |
| `/back/technology/{id}`      | `GET`        | `Informations d'une technologie`        | `displays the informations about technology`       |
| `/back/technology/{id}`      | `POST`       | `Suppression d'une technologie`         | `message before remove a technology`               |
| `/back/user`                 | `GET`        | `Liste des utilisateurs`                | `Users list`                                       |
| `/back/user/add`             | `POST`       | `Ajout d'un utilisateur`                | `Form to add a user`                               |
| `/back/user/{id}/update`     | `PUT`        | `Modification d'un utilisateur`         | `Form to update a user`                            |
| `/back/user/{id}`            | `GET`        | `Informations d'un utilisateur`         | `displays the informations about user`             |
| `/back/user/{id}`            | `POST`       | `Suppression d'un utilisateur`          | `message before remove a user`                     |

## Endpoints API

| Endpoint                                             | Méthode HTTP | Description                                                                   | Retour               |
| ---------------------------------------------------- | ------------ | ----------------------------------------------------------------------------- | -------------------- |
| `/api/v1/addresses`                                  | `GET`        | get all a addresses                                                           | 200                  |
| `/api/v1/addresses`                                  | `POST`       | add a address                                                                 | 201 + created object |
| `/api/v1/addresses/{id}`                             | `GET`        | get informations of a address                                                 | 200 or 404           |
| `/api/v1/addresses/{id}`                             | `PUT`        | update a address                                                              | 200, 204 or 404      |
| `/api/v1/addresses/{id}`                             | `DELETE`     | delete a address                                                              | 200, 204 or 404      |
| `/api/v1/candidates`                                 | `GET`        | get all candidates                                                            | 200                  |
| `/api/v1/candidates`                                 | `POST`       | add a candidate                                                               | 201 + created object |
| `/api/v1/candidates/{id}`                            | `GET`        | get informations of a candidate                                               | 200 or 404           |
| `/api/v1/candidates/{id}`                            | `PUT`        | update a candidate                                                            | 200, 204 or 404      |
| `/api/v1/candidates/{id}`                            | `DELETE`     | delete a candidate                                                            | 201 + created object |
| `/api/v1/candidates/{id}/jobs/match`                 | `GET`        | get all jobs matched for a candidate                                          | 200 or 404           |
| `/api/v1/candidates/{id}/jobs/interested`            | `GET`        | get all jobs liked for a candidate                                            | 200 or 404           |
| `/api/v1/candidates/{id}/jobs/interested_recruiter`  | `GET`        | get all jobs for which the recruiter is interested in the candidate's profile | 200 or 404           |
| `/api/v1/candidates/possible-match-job/{id}`         | `GET`        | get all candidates possible to matched with job                               | 200 or 404           |
| `/api/v1/companies`                                  | `GET`        | get all companies                                                             | 200                  |
| `/api/v1/companies`                                  | `POST`       | add a company                                                                 | 201 + created object |
| `/api/v1/companies/{id}`                             | `GET`        | get informations of a companies                                               | 200 or 404           |
| `/api/v1/companies/{id}`                             | `PUT`        | update a company                                                              | 200, 204 or 404      |
| `/api/v1/companies/{id}`                             | `DELETE`     | delete a company                                                              | 200, 204 or 404      |
| `/api/v1/contracts`                                  | `GET`        | get all contracts                                                             | 200                  |
| `/api/v1/contracts`                                  | `POST`       | add a contract                                                                | 201 + created object |
| `/api/v1/contracts/{id}`                             | `GET`        | get informations of a contract                                                | 200 or 404           |
| `/api/v1/contracts/{id}`                             | `PUT`        | update a contract                                                             | 200, 204 or 404      |
| `/api/v1/contracts/{id}`                             | `DELETE`     | delete a contract                                                             | 200, 204 or 404      |
| `/api/v1/experiences`                                | `GET`        | get all experiences                                                           | 200                  |
| `/api/v1/experiences`                                | `POST`       | add a experiences                                                             | 201 + created object |
| `/api/v1/experiences/{id}`                           | `GET`        | get informations of a experience                                              | 200 or 404           |
| `/api/v1/experiences/{id}`                           | `PUT`        | update a experience                                                           | 200, 204 or 404      |
| `/api/v1/experiences/{id}`                           | `DELETE`     | delete informations of a experience                                           | 200, 204 or 404      |
| `/api/v1/jobs`                                       | `GET`        | get all jobs                                                                  | 200                  |
| `/api/v1/jobs`                                       | `POST`       | add a job                                                                     | 201 + created object |
| `/api/v1/jobs/{id}`                                  | `GET`        | get informations of a job                                                     | 200 or 404           |
| `/api/v1/jobs/{id}`                                  | `PUT`        | update a job                                                                  | 200, 204 or 404      |
| `/api/v1/jobs/{id}`                                  | `DELETE`     | delete a job                                                                  | 200, 204 or 404      |
| `/api/v1/jobs/possible-match-candidate/{id}`         | `GET`        | get all jobs possible to matched with candidate                               | 200 or 404           |
| `/api/v1/jobs/recruiters/{id}`                       | `GET`        | get all jobs of a recruiter                                                   | 200 or 404           |
| `/api/v1/jobs/candidate-interrested`                 | `PUT`        | add an interest for a job by a candidate                                      | 200, 204 or 404      |
| `/api/v1/jobs/recruiter-interrested`                 | `PUT`        | add an interest for a job by a recruiter                                      | 200, 204 or 404      |
| `/api/v1/jobtitles`                                  | `GET`        | get all jobtitles                                                             | 200                  |
| `/api/v1/jobtitles`                                  | `POST`       | add a jobtitle                                                                | 201 + created object |
| `/api/v1/jobtitles/{id}`                             | `GET`        | get informations of a jobtitle                                                | 200 or 404           |
| `/api/v1/jobtitles/{id}`                             | `PUT`        | update a jobtitle                                                             | 200, 204 or 404      |
| `/api/v1/jobtitles/{id}`                             | `DELETE`     | delete a jobtitle                                                             | 200, 204 or 404      |
| `/api/v1/matchups`                                   | `GET`        | get all matchups                                                              | 200                  |
| `/api/v1/matchups/{id}`                              | `GET`        | get informations of a matchup                                                 | 200 or 404           |
| `/api/v1/matchups/{id}`                              | `DELETE`     | delete a matchup                                                              | 200, 204 or 404      |
| `/api/v1/recruiters`                                 | `GET`        | Get all recruiters                                                            | 200                  |
| `/api/v1/recruiters`                                 | `POST`       | add a recruiter                                                               | 201 + created object |
| `/api/v1/recruiters/{id}`                            | `GET`        | get informations of a recruiter                                               | 200                  |
| `/api/v1/recruiters/{id}`                            | `PUT`        | update a recruiter                                                            | 200, 204 or 404      |
| `/api/v1/recruiters/{id}`                            | `DELETE`     | delete a recruiter                                                            | 200, 204 or 404      |
| `/api/v1/recruiters/{id}/jobs/match`                 | `GET`        | get all jobs matched for a recruiter                                          | 200 or 404           |
| `/api/v1/recruiters/{id}/jobs/interested`            | `GET`        | get all jobs liked for a recruiter                                            | 200 or 404           |
| `/api/v1/recruiters/jobs/{id}/candidates-interested` | `GET`        | get all candidates interested by a job                                        | 200 or 404           |
| `/api/v1/salaries`                                   | `GET`        | get all salary-brackets                                                       | 200                  |
| `/api/v1/salaries`                                   | `POST`       | add a salary-bracket                                                          | 201 + created object |
| `/api/v1/salaries/{id}`                              | `GET`        | get informations of a salary-bracket                                          | 200 or 404           |
| `/api/v1/salaries/{id}`                              | `PUT`        | update a salary-bracket                                                       | 200, 204 or 404      |
| `/api/v1/salaries/{id}`                              | `DELETE`     | delete a salary-bracket                                                       | 200, 204 or 404      |
| `/api/v1/sectors`                                    | `GET`        | get all sectors                                                               | 200                  |
| `/api/v1/sectors`                                    | `POST`       | add a sector                                                                  | 201 + created object |
| `/api/v1/sectors/{id}`                               | `GET`        | get informations of a sector                                                  | 200 or 404           |
| `/api/v1/sectors/{id}`                               | `PUT`        | update informations of a sector                                               | 200, 204 or 404      |
| `/api/v1/sectors/{id}`                               | `DELETE`     | delete a sector                                                               | 200, 204 or 404      |
| `/api/v1/technologies`                               | `GET`        | get all technologies                                                          | 200                  |
| `/api/v1/technologies`                               | `POST`       | add a technology                                                              | 201 + created object |
| `/api/v1/technologies/{id}`                          | `GET`        | get informations of a technology                                              | 200 or 404           |
| `/api/v1/technologies/{id}`                          | `PUT`        | update a technology                                                           | 200, 204 or 404      |
| `/api/v1/technologies/{id}`                          | `DELETE`     | get informations of a technology                                              | 200, 204 or 404      |
| `/api/v1/users`                                      | `GET`        | get all users                                                                 | 200                  |
| `/api/v1/users/{id}/profil`                          | `GET`        | get user profil                                                               | 200 or 404           |
| `/api/v1/subscribe`                                  | `POST`       | add new user                                                                  | 201 + created object |
| `/api/v1/users/{id}`                                 | `PUT`        | update a user                                                                 | 200, 204 or 404      |
| `/api/v1/users/{id}`                                 | `DELETE`     | delete a user                                                                 | 200, 204 or 404      |
| `/api/v1/login`                                      | `POST`       | connect user                                                                  | 200 or 401           |
| `/api/v1/logout`                                     | `GET`        | deconnect user                                                                | 200                  |
| `/api/v1/getuser`                                    | `GET`        | get user to connect                                                           | 200                  |
