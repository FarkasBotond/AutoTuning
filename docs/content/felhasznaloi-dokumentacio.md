# Felhasználói dokumentáció

Ez a dokumentum az alkalmazás alábbi funkcionális területeit foglalja össze:
- Felhasználókezelés és hitelesítés
- Márkák és modellek adminisztratív kezelése

## 1. Fiókkezelés és bejelentkezés

### Regisztráció
1. Nyissa meg a `Regisztráció` oldalt (`/register`).
2. Töltse ki a név, e-mail-cím, jelszó és jelszó-megerősítés mezőket.
3. Sikeres regisztráció esetén a rendszer automatikusan bejelentkezteti.

Követelmények:
- Az e-mail mezőben érvényes e-mail-formátum megadása szükséges.
- A jelszónak legalább 8 karakter hosszúnak kell lennie.

### Bejelentkezés
1. Nyissa meg a `Bejelentkezés` oldalt (`/login`).
2. Adja meg az e-mail-címet és a jelszót.
3. Sikeres bejelentkezés után a védett oldalak elérhetővé válnak.

### Kijelentkezés
- A kijelentkezés érvényteleníti a fiókhoz tartozó aktuális hozzáférési tokent.

### Profil módosítása (`/profile`)
A profiloldalon két fő művelet érhető el:
- **E-mail-cím módosítása**: az új e-mail-cím és a jelenlegi jelszó megadása szükséges.
- **Jelszó módosítása**: a jelenlegi jelszó, az új jelszó és annak megerősítése szükséges.

Megjegyzés:
- A profiloldal kizárólag bejelentkezett felhasználók számára érhető el.

## 2. Márkák és modellek kezelése (admin)

Az adminisztrációs felület (`/admin`) kizárólag admin jogosultsággal érhető el.

### Márkák kezelése (`/admin/brands`)
- Márkalista megtekintése
- Új márka létrehozása
- Márka szerkesztése
- Márka törlése

Fontos:
- Márka törlésekor a hozzá kapcsolódó modellek is törlésre kerülnek.

### Modellek kezelése (`/admin/models`)
- Modelllista megtekintése
- Szűrés márka és gyártási év szerint
- Új modell létrehozása
- Modell szerkesztése
- Modell törlése

Egy modell esetében az alábbi adatok adhatók meg:
- Márka
- Modellnév
- Kezdő év
- Záró év (opcionális; amennyiben nincs megadva, a gyártás folyamatban lévőnek tekintendő)

## 3. Jogosultságok röviden

- Nyilvános oldalak: például főoldal, bejelentkezés, regisztráció.
- Bejelentkezéshez kötött oldalak: például profil.
- Admin oldalak: kizárólag admin szerepkörrel (`role = admin`).

## 4. Gyakori hibák

- **„Hibás e-mail vagy jelszó”**: ellenőrizze a megadott bejelentkezési adatokat.
- **„Unauthorized / 403”**: nem megfelelő jogosultság (nem admin, vagy nincs bejelentkezve).
- **Mentési hiba profilmódosításkor**: gyakori ok a hibás jelenlegi jelszó.

## 10. Főoldal működése (/ - index.vue)

A főoldal az alkalmazás központi felülete, ahol a felhasználó a tuning termékeket és cégeket böngészheti.

Megjelenített elemek

A főoldal az alábbi komponensekből épül fel:

BaseHeadLine
Az oldal címe és vizuális fejléc
SideMenu
Kategóriák vagy szűrési lehetőségek megjelenítése
ProductCard
Egy adott termék megjelenítése (kártya formában)
TuningCompaniesSection
Kiemelt tuning cégek listája
BaseFooter
Oldal alja (kapcsolati információk)
Toast
Visszajelző üzenetek (pl. sikeres művelet)
Adatkezelés

A főoldal a tuningProductStore (Pinia store) segítségével kezeli az adatokat.

Főbb jellemzők:

A termékek betöltése a backend API-ból történik
Az adatok reaktívan frissülnek
A komponensek automatikusan újrarenderelődnek változás esetén
Lapozás (Pagination)

A főoldal lapozást használ a termékek megjelenítéséhez.

Működés:

currentPage tárolja az aktuális oldalt
itemsPerPage meghatározza, hány elem jelenik meg egyszerre
A megjelenített termékek számítása:
const start = (currentPage.value - 1) * itemsPerPage
A teljes oldalszám:
Math.ceil(tuningProductStore.products.length / itemsPerPage)

Szabály:

Minimum 1 oldal mindig létezik
Dinamikus terméklista

A megjelenített termékek:

Szűrve és lapozva jelennek meg
A paginatedProducts computed property számolja ki őket
Minden termék külön ProductCard komponensben jelenik meg
Toast értesítések

A rendszer vizuális visszajelzést ad:

toastVisible → megjelenítés vezérlése
toastMessage → üzenet tartalma

Példák:

Sikeres adatbetöltés
Hiba API hívásnál
Életciklus (Lifecycle)

A főoldal betöltésekor:

onMounted()

történik:

Termékek lekérése API-ból
Inicializálás
Felhasználói interakciók

A felhasználó az alábbiakat tudja:

Termékek böngészése
Lapozás oldalak között
Szűrés (SideMenu segítségével)
Tuning cégek megtekintése
Hibakezelés

Lehetséges hibák:

API nem elérhető
Üres terméklista
Lassú betöltés

Kezelés:

Toast üzenet
Üres lista esetén fallback megjelenítés
