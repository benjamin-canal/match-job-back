# Exemple de structure JSON attendue pour les routes de l'API

## `/api/v1/jobs/candidate-interrested`

* Methode HTTP
  
`POST`

* Exemple contenu JSON
  
```json
{
  "candidate": 1,
  "job": 1
}
```

## `/api/v1/jobs/recruiter-interrested`

* Methode HTTP
  
`PUT`

* Exemple contenu JSON
  
```json
{
  "job": {
    "id": 1
  },
  "candidate": {
    "id": 1
  }
}
```

## `/api/v1/subscribe`

* Methode HTTP
  
`POST`

* Exemple contenu JSON
  
```json
{
  "email": "brad.pitt@star.com",
  "password": "azerty1",
  "roles": ["ROLE_CANDIDATE"]
}
```

## `/api/v1/candidates`

* Methode HTTP
  
`POST`

* Exemple contenu JSON
  
```json
{
  "lastName": "PITT",
  "firstName": "Brad",
  "birthday": "1975-06-07",
  "phoneNumber": "+56620125478",
  "genre": 2,
  "picture": "https:\/\/picsum.photos\/id\/54\/100\/100",
  "resume": "http:\/\/www.gomez.fr\/et-maiores-delectus-dolorem-tempora-non",
  "description": "Eum omnis iusto culpa labore. A accusamus animi error cupiditate cum impedit velit nostrum.",
  "positionHeld": "Acteur",
  "portfolio": "http:\/\/henry.fr\/mollitia-voluptatibus-nemo-numquam-a-quia-et",
  "adress": 
  {
    "streetNumber": 142,
    "streetName": "Place Roger Rabbit",
    "zip": "74123",
    "city": "CarotCity",
    "department": "Isère"
  },
  "user": 2,
  "contract": 1,
  "experience": 1,
  "jobtitle": 1,
  "salary": 1,
  "technologies":[
    1,
    5,
    10
  ]
}
```
