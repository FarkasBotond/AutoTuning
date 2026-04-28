# Fejlesztői dokumentáció

Ez a dokumentáció a következő modulok technikai összefoglalója:
- Felhasználókezelés és authentikáció
- Márkák és modellek admin CRUD

## 1. Backend összefoglaló

### Fő route fájl
- `backend/routes/api.php`

### Auth és user endpointok
- `POST /registration` – regisztráció
- `POST /login` – bejelentkezés (Sanctum token kiadás)
- `POST /logout` – kijelentkezés (`auth:sanctum`)
- `PUT /profile/email` – email módosítás (`auth:sanctum`)
- `PUT /profile/password` – jelszó módosítás (`auth:sanctum`)

Fő controllerek:
- `backend/app/Http/Controllers/RegistrationController.php`
- `backend/app/Http/Controllers/AuthController.php`
- `backend/app/Http/Controllers/UserController.php`

Validáció:
- `backend/app/Http/Requests/StoreUserRequest.php`
- `backend/app/Http/Requests/LoginRequest.php`

### Márka és modell endpointok
Nyilvános olvasás:
- `GET /car-brands`
- `GET /car-brands/{carBrand}`
- `GET /car-models`
- `GET /car-models/{carModel}`

Admin CRUD (`auth:sanctum` + `admin` middleware):
- `POST /car-brands`
- `PUT /car-brands/{carBrand}`
- `DELETE /car-brands/{carBrand}`
- `POST /car-models`
- `PUT /car-models/{carModel}`
- `DELETE /car-models/{carModel}`

Fő controllerek:
- `backend/app/Http/Controllers/CarBrandController.php`
- `backend/app/Http/Controllers/CarModelController.php`

Validáció/authorize:
- `backend/app/Http/Requests/StoreCarBrandRequest.php`
- `backend/app/Http/Requests/UpdateCarBrandRequest.php`
- `backend/app/Http/Requests/StoreCarModelRequest.php`
- `backend/app/Http/Requests/UpdateCarModelRequest.php`

Admin jogosultság middleware:
- `backend/app/Http/Middleware/IsAdmin.php`

## 2. Frontend összefoglaló

### Auth állapotkezelés
- `frontend/src/stores/authStore.js`

Fő mezők:
- `token`, `user`, `isAuthenticated`, `isAdmin`

Fő műveletek:
- `login(email, password)`
- `logout()`

### API kliens
- `frontend/src/lib/api.js`

Releváns függvények:
- Auth: `login`, `logout`, `updateProfileEmail`, `updateProfilePassword`
- Brand: `fetchBrands`, `fetchBrand`, `createBrand`, `updateBrand`, `deleteBrand`
- Model: `fetchModels`, `fetchModel`, `createModel`, `updateModel`, `deleteModel`

### Route védelem
- `frontend/src/router/guards/AuthGuard.mjs`

Logika:
- `meta.requiresAuth` esetén bejelentkezés kötelező.
- `meta.role === 'admin'` esetén admin szerepkör kötelező (pl.: admin oldalak).
- Public route lista explicit kezelve van.

## 3. Admin oldalak és store-ok

Oldalak:
- `frontend/src/pages/admin/index.vue`
- `frontend/src/pages/admin/brands.vue`
- `frontend/src/pages/admin/brands.[id].vue`
- `frontend/src/pages/admin/models.vue`
- `frontend/src/pages/admin/models.[id].vue`

Store-ok:
- `frontend/src/stores/brandStore.js`
- `frontend/src/stores/modelStore.js`

Űrlap komponensek:
- `frontend/src/components/Forms/BrandForm.vue`
- `frontend/src/components/Forms/ModelForm.vue`

## 4. Fontos fejlesztői megjegyzések

- A backend oldali validáció az irányadó (frontend validáció csak UX).
- A model store normalizálja a bejövő/kimenő adatokat (`normalizeIncomingModel`, `normalizeOutgoingModel`) a régi és új mezőformátum kompatibilitásához.
- A profil módosításokhoz kötelező a jelenlegi jelszó.
- Az admin műveletek minden szinten védettek:
  - route middleware (`admin`),
  - request `authorize()` ellenőrzés.


5. Főoldal architektúra (frontend/src/pages/index.vue)

Az index.vue a frontend központi entry oldala, amely a tuning termékek listázását és megjelenítését végzi.

Komponens struktúra

Az oldal komponensekből épül fel:

BaseHeadLine – oldal fejléc
SideMenu – szűrési/kategória panel
ProductCard – egy termék megjelenítése
TuningCompaniesSection – tuning cégek blokk
BaseFooter – lábléc
Toast – visszajelző üzenetek

Az oldal layout-ja grid alapú és reszponzív.

State kezelés

A komponens a következő reaktív state-eket használja:

const currentPage = ref(1)
const itemsPerPage = 6

const toastVisible = ref(false)
const toastMessage = ref('')

Külső state:

const tuningProductStore = useTuningProductStore()
Adatfolyam (Data Flow)
Komponens mount:
onMounted()
Store hívás:
tuningProductStore.fetchProducts() (vagy hasonló)
API hívás:
backend → termék lista
Store frissül:
products tömb
UI automatikusan frissül (Vue reaktivitás)
Pagination logika

A lapozás computed property-kkel történik:

const totalPages = computed(() => {
    const total = Math.ceil(tuningProductStore.products.length / itemsPerPage)
    return total > 0 ? total : 1
})
const paginatedProducts = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage
    return tuningProductStore.products.slice(start, start + itemsPerPage)
})

Megjegyzés:

Mindig legalább 1 oldal van
Slice alapú pagination (frontend oldali)
Rendering logika

A template-ben:

v-for iterál a paginatedProducts listán
Minden elem egy ProductCard komponens
<ProductCard
  v-for="product in paginatedProducts"
  :key="product.id"
  :product="product"
/>
Store integráció

A tuningProductStore felel:

API kommunikációért
State kezelésért
Hibakezelésért

Tipikus store mezők:

products
loading
error
Toast rendszer

Visszajelzés kezelés:

Siker → toast megjelenik
Hiba → toast hibaüzenettel
toastMessage.value = 'Hiba történt'
toastVisible.value = true