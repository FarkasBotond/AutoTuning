# RaceDistrict

IKT projektmunka Vizsgaremek RaceDistrict - Az RaceDistrict egy komplex webalkalmazás, amely lehetővé teszi a felhasználók számára, hogy autótuning szolgáltatásokat böngészzenek, összehasonlítsanak és szervezzék. A platform összeköti az autós rajongókat a professzionális tuning cégekkel, ahol megtalálhatók az autómárkák, modellek, tuning cégek és az általuk nyújtott szolgáltatások.

**Készítők:** Pongrácz Gábor, Farkas Botond, Bárdos Simon

---

## 🚀 Gyors kezdés - Projekt induláss 0-ról

### Előfeltételek

Az RaceDistrict projekt futtatásához az alábbiak szükségesek:

- **Docker** és **Docker Compose** telepítve (legalább Docker Engine 20.10+, Compose v2+)
- **Git** verziókezelő rendszer
- **Linux/macOS vagy Windows WSL2** operációs rendszer
- Minimálisan **8 GB RAM** a Docker tárolók futtatásához
- Internetes kapcsolat

### 1. lépés: Projekt klónozása / Update

Nyissa meg a terminált és keresse meg azt a mappát, ahova szeretné telepíteni a projektet:

```bash
# Ha még nincs meg a projekt mappa, klónozza le a repository-ból:
git clone <repository-url>

# Ha már létezik a projekt, frissítse az legújabb verzióra:
git pull origin main
```

### 2. lépés: Start script futtatása

Az összes szükséges Docker konténer automatikus indulásakor használja az egyszerű start scriptet:

```bash
./start.sh
```

**Mit csinál a start.sh script?**

1. Ellenőrzi, hogy létezik-e a `.env` fájl, és ha nem, másolja az `.env.example`-ből
2. Létrehozza a szükséges Docker volume-okat (`shared_pnpm`, `shared_composer`)
3. Telepíti a frontend npm függőségeket (pnpm)
4. Elindítja az összes Docker konténert
5. Telepíti a backend Composer csomagokat
6. Futtatja az adatbázis migrációkat
7. Generálja az alkalmazás titkos kulcsot (ha még nem létezik)

**Tipikus futásidő:** 3-5 perc az első indítás esetén

### 3. lépés: Az alkalmazás elérése

Az RaceDistrict projekt indítása után az alábbi URL-eken érhetők el a szolgáltatások:

#### Frontend alkalmazás (Vue.js) - **Fő felhasználói felület**
- **URL:** `http://frontend.vm1.test`
- **Port:** 80 (proxy-n keresztül)
- **Leírás:** A modern, responsív webalkalmazás, ahol a felhasználók böngészhetnek, regisztrálhatnak és rendeléseket helyezhetnek el

#### Backend API (Laravel) - **Alkalmazás logika**
- **URL:** `http://backend.vm1.test/api`
- **Port:** 80 (proxy-n keresztül)
- **Leírás:** RESTful API endpoint-ok az összes adat kezeléséhez

#### phpMyAdmin - **Adatbázis kezelő**
- **URL:** `http://pma.vm1.test`
- **Port:** 80 (proxy-n keresztül)
- **Felhasználó:** (az `.env` fájl szerint)
- **Jelszó:** (az `.env` fájl szerint)
- **Leírás:** Grafikus adatbázis adminisztrátor MySQL adatbázis módosításához

#### API Dokumentáció (Swagger/OpenAPI)
- **URL:** `http://swagger.vm1.test`
- **Port:** 80 (proxy-n keresztül)
- **Leírás:** Interaktív API dokumentáció és tesztelési felület az összes endpoint-hoz

#### JSON Server - **Mock adatok szerver**
- **URL:** `http://jsonserver.vm1.test`
- **Port:** 80 (proxy-n keresztül)
- **Leírás:** Fejlesztési célú mock adatszolgáltatás

#### Mail catcher - **Email tesztelés**
- **URL:** `http://mailcatcher.vm1.test`
- **Port:** 80 (proxy-n keresztül)
- **Leírás:** Fejlesztés során elküldött emailek megtekintésére

#### Dokumentáció
- **URL:** `http://docs.vm1.test`
- **Port:** 80 (proxy-n keresztül)
- **Leírás:** Fejlesztői dokumentáció és projekt információk

### 4. lépés: A .env fájl konfigurálása (szükség esetén)

Ha módosítani szeretnék a projekt beállításait, nyissa meg a `.env` fájlt a projekt gyökerében:

```bash
nano .env
# vagy egyéb szövegszerkesztővel
```

Módosítás után indítsa újra a Docker konténereket:

```bash
docker compose restart
```

---

## 📋 Az alkalmazás funkcionalitása

Az RaceDistrict webalkalmazás az alábbi fő modulokból és funkcionalitásokból áll:

### Felhasználói funkciók

#### Regisztráció és Bejelentkezés
- **Regisztráció:** Új felhasználók regisztrálhatnak email cím és jelszó megadásával
- **Email validáció:** Az email cím formátumellenőrzésre kerül (pl. `user@gmail.com`)
- **Jelszó követelmények:** Minimum 8 karakter hosszú jelszó szükséges
- **Regisztrációs confirm:** A jelszót kétszer be kell írni az elírások elkerülésére
- **Bejelentkezés:** Regisztrált felhasználók bejelentkezhetnek az alkalmazásba
- **Alap admin fiók:** 'name' => 'Admin User',
                        'email' => 'admin@gmail.com',
                        'password' => bcrypt('12345678'),

- **Jelszókezelés:** A jelszavak bcrypt algoritmussal vannak hashelve az adatbázisban (pl. `$2y$12$Inrh8bebaiF4TvyQOtzJRuQVva4ZOiqGZWA2uPnKTWS...`)

#### Autómárkák és Modellek Böngészése
- **Autómárkák listázása:** Az összes elérhető autómárka megtekintése
- **Autómodellek:** Az egyes márkákhoz tartozó modellek listázása
- **Részletes nézet:** Az autók és modellek részletes információinak megtekintése

#### Tuning Cégek
- **Cégek böngészése:** Az összes regisztrált tuning cég megtekintése
- **Cég profilja:** A tuning cégekhez tartozó információk, típusok, autómodellek amit támogatnak
- **Szolgáltatások:** Az egyes cégek által kínált tuning szolgáltatások megtekintése

#### Tuning Szolgáltatások
- **Szolgáltatás kategóriák:** Az elérhető tuning szolgáltatások kategóriai (motor tuning, külső módosítások, etc.)
- **Tuning termékek:** A konkrét tuning termékek és szolgáltatások részletei, árai
- **Szűrés:** Szűrés autó márkák és modellek alapján

#### Rendelések
- **Rendelés létrehozása:** Felhasználók rendeléseket helyezhetnek el a tuning szolgáltatásokra
- **Rendelés nyomonkövetés:** A saját rendelések megtekintése és státuszának követése
- **Rendelési adatok:** A teljes rendelési előzmények és részletei

### Adminisztrációs funkciók (Fejlesztői nézet)

#### Adatbázis Kezelés (phpMyAdmin)
- Az összes tábla közvetlen módosítása
- Felhasználók, autók, cégek, rendelések kezelése
- Adatbázis biztonsági mentések

#### API Tesztelés (Swagger)
- Az összes API endpoint interaktív tesztelése
- Request/Response vizualizáció
- Authentikáció tesztelése

---

## 💻 Technológiai stack

### Frontend
- **Vue.js 3** - Progressív JavaScript keretrendszer
- **Vite** - Modern JavaScript bundler
- **Pnpm** - Gyors package manager
- **Responsive design** - Mobilbarát felület

### Backend
- **Laravel 12** - PHP webalkalmazás keretrendszer
- **PHP 8.3+** - Szerver oldali programozás
- **MySQL 9.3** - Relációs adatbázis

### DevOps & Deployment
- **Docker & Docker Compose** - Konténerizáció és oresztráció
- **Nginx** - Webszerver és proxy
- **phpMyAdmin** - Adatbázis adminisztráció

---

## 🐳 Docker konténerek

Az RaceDistrict projekt a következő Docker konténereket indítja el:

| Konténer | Leírás | Port |
|----------|--------|------|
| **proxy** | Nginx proxy az összes service-hez | 80 |
| **frontend** | Vue.js alkalmazás | docker hálózat |
| **backend** | Laravel API szerver | docker hálózat |
| **db** | MySQL adatbázis | docker hálózat |
| **phpmyadmin** | Adatbázis adminisztráció | docker hálózat |
| **webserver** | Nginx webszerver | docker hálózat |
| **swagger** | API dokumentáció | docker hálózat |
| **jsonserver** | Mock API szerver | docker hálózat |
| **docs** | Projekt dokumentáció | docker hálózat |
| **mailcatcher** | Email fejlesztési eszköz | docker hálózat |

### Docker parancsok

```bash
# Konténerek állapotának ellenőrzése
docker compose ps

# Konténer logok megtekintése
docker compose logs frontend
docker compose logs backend
docker compose logs db

# Egy konténer újraindítása
docker compose restart backend

# Összes konténer leállítása
docker compose down

# Konténerek leállítása és az összes adat törlése (vigyázat!)
docker compose down -v
```

---

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
