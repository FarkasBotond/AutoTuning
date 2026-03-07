# AutoTuning
IKT projektmunka Vizsgaremek AutoTuning
Készítők: Pongrácz Gábor, Farkas Botond, Bárdos Simon


# Fejlesztői leírás

## Backend
Laravel 12-es verziót használtunk.

### Migration
Migráció létrehozza az adatbázis táblákat az alábbiak alapján:
- [EK diagram](https://drive.google.com/file/d/1gWXXCyXQHy77fjwFa_Fh1NEHcDnPUdQ9/view?usp=sharing)
- [Adatbázis táblák](https://drive.google.com/file/d/15oZNEP6bKUFaIVEMQExjgZcAidSgLBV_/view?usp=sharing)

### Seeder
A seederek feladata, hogy a migrációk lefuttatása után kezdő adatokat töltsenek az adatbázisba.

A DatabaseSeeder ebben a sorrendben hívja meg, hogy minden idegen kulcsos kapcsolat biztosan érvényes legyen:
- **CarBrandSeeder**: létrehozza az autómárkákat.
- **CarModelSeeder**: létrehozza az autómodelleket. 
- **TuningCompanySeeder**: létrehozza a tuning cégeket.
- **ServiceCategorySeeder**: létrehozza a tuning kategóriákat.
- **CompanyCarModelDeeder**: létrehozza a cég és autómodell közötti kapcsolatokat.
- **CompanyServiceSeeder**: létrehozza a cég és szolgáltatás kategória közötti kapcsolatokat.
