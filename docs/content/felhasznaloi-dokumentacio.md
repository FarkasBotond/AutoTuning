# Felhasználói dokumentáció

Ez az oldal a következő funkciókat foglalja össze:
- Felhasználókezelés és authentikáció
- Márkák és modellek admin kezelése

## 1. Fiókkezelés és bejelentkezés

### Regisztráció
1. Nyisd meg a `Regisztráció` oldalt (`/register`).
2. Töltsd ki a nevet, email címet, jelszót és jelszó megerősítést.
3. Sikeres regisztráció után a rendszer automatikusan bejelentkeztet.

Követelmények:
- Érvényes email fromátum az email mezőben.
- Jelszó minimum 8 karakter.

### Bejelentkezés
1. Nyisd meg a `Bejelentkezés` oldalt (`/login`).
2. Add meg az email címet és jelszót.
3. Siker esetén a rendszer bejelentkeztet, és elérhetővé válnak a védett oldalak.

### Kijelentkezés
- A kijelentkezés a fiókhoz tartozó aktuális hozzáférési tokent érvényteleníti.

### Profil módosítás (`/profile`)
A profil oldalon két fő művelet van:
- **Email cím módosítása**: új email + jelenlegi jelszó megadása szükséges.
- **Jelszó módosítása**: jelenlegi jelszó + új jelszó + megerősítés.

Megjegyzés:
- Profil oldalt csak bejelentkezett felhasználó éri el.

## 2. Márkák és modellek kezelése (admin)

Az admin felület (`/admin`) csak admin jogosultsággal érhető el.

### Márkák kezelése (`/admin/brands`)
- Márka lista megtekintése.
- Új márka létrehozása.
- Márka szerkesztése.
- Márka törlése.

Fontos:
- Márka törlésekor a hozzá kapcsolódó modellek is törlődnek.

### Modellek kezelése (`/admin/models`)
- Modell lista megtekintése.
- Szűrés márka és gyártási év szerint.
- Új modell létrehozása.
- Modell szerkesztése.
- Modell törlése.

Egy modellnél megadható:
- Márka
- Modellnév
- Kezdő év
- Záró év (opcionális, ha nem adunk meg azt jelenti hogy a gyártás még nem fejeződött be)

## 3. Jogosultságok röviden

- Nyilvános oldalak: pl. főoldal, bejelentkezés, regisztráció.
- Bejelentkezéshez kötött oldalak: pl. profil.
- Admin oldalak: csak admin szerepkörrel (`role = admin`).

## 4. Gyakori hibák

- **„Hibás email vagy jelszó”**: ellenőrizd a bejelentkezési adatokat.
- **„Unauthorized / 403”**: nincs megfelelő jogosultság (nem admin vagy nem vagy bejelentkezve).
- **Mentési hiba profilnál**: gyakori ok a hibás jelenlegi jelszó.
