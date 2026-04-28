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
