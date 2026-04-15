# AutoTuning
IKT projektmunka Vizsgaremek AutoTuning
Készítők: Pongrácz Gábor, Farkas Botond, Bárdos Simon


# Fejlesztői leírás

### Bejelentkezés és regisztráció

Email cím -> email formátumúnak kell lennie (pl.: xxx@yyy.zzz)
Jelszó -> minimum 8 karakter, regisztrációnál 2x kell beírni hogy iztos ne történjen elírás. Hash-elve van eltárolva, pma.vm1.test-en így jelenik meg pl. egy jelszó: $2y$12$Inrh8bebaiF4TvyQOtzJRuQVva4ZOiqGZWA2uPnKTWS...

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


## Validáció

A backend Laravel beépített validációs rendszerét használja annak érdekében, hogy az API-ba érkező adatok megfeleljenek az adatbázis struktúrájának és a logikai feltételeknek.

A validációk az egyes entitásokhoz tartozó **Request osztályokban** kerültek meghatározásra.

### CarBrand
Az autómárka létrehozásakor a következő feltételek érvényesek:

- **name**: kötelező mező  
- **string típusú**  
- **maximum 25 karakter hosszú**  
- **egyedi az adatbázisban**

### CarModel
Az autómodell létrehozásakor az alábbi feltételek vannak:

- **name**: kötelező mező  
- **string típusú**  
- **maximum 50 karakter**  
- **brand_id**: kötelező mező  
- **létező autómárkára kell hivatkoznia**

### TuningCompany
A tuning cég létrehozásakor:

- **name**: kötelező mező  
- **string típusú**  
- **maximum 50 karakter**

### ServiceCategory
A szolgáltatás kategória létrehozásakor:

- **name**: kötelező mező  
- **string típusú**  
- **maximum 100 karakter**  
- **egyedi az adatbázisban**

### CompanyCarModel
A cég és autómodell kapcsolat létrehozásakor:

- **company_id**: kötelező mező  
- **létező tuning cégre kell hivatkoznia**  
- **model_id**: kötelező mező  
- **létező autómodellre kell hivatkoznia**

### CompanyService
A cég és szolgáltatás kapcsolat létrehozásakor:

- **company_id**: kötelező mező  
- **létező tuning cégre kell hivatkoznia**  
- **category_id**: kötelező mező  
- **létező szolgáltatás kategóriára kell hivatkoznia**
