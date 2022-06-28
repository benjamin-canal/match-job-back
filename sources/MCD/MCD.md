# MCD (via Mocodo)

```txt
SECTOR: name
HAS, 11 COMPANY, 0N SECTOR
COMPANY: name
::

ADRESS: street number, street name, zip, city, department
IS DOMICILED, 11 CANDIDATE, 1N ADRESS, 11 COMPANY
:
USER: email, password, role, is helped
BELONGS TO, 11 RECRUITER, 1N COMPANY
:
RECRUITER: last name, first name, phone number

:

::
CAN BE, 0N USER, 11 RECRUITER, 11 CANDIDATE
:::
OFFERS, 0N RECRUITER, 11 JOB

MATCHUP, 0N CANDIDATE, 0N JOB: candidate status, recruiter status, match status

TECHNOLOGY: name, background color, text color

SPECIALISED, 1N CANDIDATE, 0N TECHNOLOGY, 1N JOB

CANDIDATE: last name, first name, birth day, phone number, picture, resume, description, position held, portfolio
:::::
JOB: name, description, status

WANT, 11 CANDIDATE, 1N SALARY, 1N JOBTITLE, 1N CONTRACT, 1N EXPERIENCE, 11 JOB

CONTRACT:name
:
EXPERIENCE: years number
:
JOBTITLE: title
:
SALARY:name
```
