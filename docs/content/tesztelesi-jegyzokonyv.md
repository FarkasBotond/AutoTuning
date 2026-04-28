# Tesztelési jegyzőkönyv

## Projekt és környezet

- Projekt: AutoTuning
- Dátum: 2026-04-27
- Backend: Laravel Feature testek (`php artisan test`)
- Frontend: Playwright E2E testek Docker konténerben

## Alkalmazott tesztelési eszközök

- Backend automatizált tesztelés: `Laravel Feature` tesztek, futtatás `php artisan test` paranccsal
- Frontend automatizált tesztelés: `Playwright` E2E tesztek, futtatás konténeren belül (`pnpm test:e2e`)
- Konténerizált környezet és futtatás: `Docker Compose`

## Szerzőségi megjegyzés

- A jelen jegyzőkönyvben felsorolt tesztesetek Bárdos Simon munkái.

## Futtatott parancsok és eredmények

### Backend

```bash
docker compose exec backend php artisan test
```

- Eredmény: **PASS**
- Összesített kimenet: **65 passed**, **171 assertions**

### Frontend E2E

```bash
docker compose exec -e PLAYWRIGHT_CHROMIUM_EXECUTABLE_PATH=/usr/bin/chromium frontend pnpm test:e2e
```

- Eredmény: 
- Összesített kimenet: 

## Backend tesztesetek (minden egyes teszt)

### `Tests\Feature\AuthControllerTest`

1. `test_user_can_login_with_valid_credentials`
2. `test_login_fails_with_invalid_credentials`
3. `test_login_fails_with_unknown_email`
4. `test_login_validation_fails_when_email_is_missing`
5. `test_login_validation_fails_when_password_is_too_short`
6. `test_login_response_contains_user_name_and_role`
7. `test_logout_requires_authentication`
8. `test_authenticated_user_can_logout_and_current_token_is_revoked`
9. `test_logout_revokes_only_current_token`

### `Tests\Feature\AdminGuardTest`

1. `test_admin_route_requires_authentication`
2. `test_non_admin_user_cannot_access_admin_route`
3. `test_admin_user_can_access_admin_route`
4. `test_unauthenticated_user_cannot_update_brand`
5. `test_non_admin_user_cannot_update_brand`
6. `test_admin_user_can_update_brand`
7. `test_unauthenticated_user_cannot_delete_brand`
8. `test_non_admin_user_cannot_delete_brand`
9. `test_admin_user_can_delete_brand`
10. `test_unauthenticated_user_cannot_create_model`
11. `test_non_admin_user_cannot_create_model`
12. `test_admin_user_can_create_model`
13. `test_unauthenticated_user_cannot_update_model`
14. `test_non_admin_user_cannot_update_model`
15. `test_admin_user_can_update_model`
16. `test_unauthenticated_user_cannot_delete_model`
17. `test_non_admin_user_cannot_delete_model`
18. `test_admin_user_can_delete_model`

### `Tests\Feature\CarBrandControllerTest`

1. `test_unauthenticated_user_can_list_brands`
2. `test_non_admin_user_can_list_brands`
3. `test_admin_can_list_brands`
4. `test_admin_can_create_brand`
5. `test_non_admin_cannot_create_brand`
6. `test_create_brand_validation_fails_without_name`
7. `test_create_brand_validation_fails_with_duplicate_name`
8. `test_admin_can_show_brand`
9. `test_admin_can_update_brand`
10. `test_non_admin_cannot_update_brand`
11. `test_admin_can_delete_brand`
12. `test_non_admin_cannot_delete_brand`
13. `test_delete_brand_cascades_to_models`

### `Tests\Feature\CarModelControllerTest`

1. `test_unauthenticated_user_can_list_models`
2. `test_non_admin_user_can_list_models`
3. `test_admin_can_list_models`
4. `test_list_models_includes_brand_relationship`
5. `test_admin_can_create_model`
6. `test_non_admin_cannot_create_model`
7. `test_create_model_validation_fails_without_required_fields`
8. `test_create_model_validation_fails_with_invalid_brand`
9. `test_admin_can_show_model`
10. `test_admin_can_update_model`
11. `test_non_admin_cannot_update_model`
12. `test_admin_can_delete_model`
13. `test_non_admin_cannot_delete_model`
14. `test_model_validation_requires_valid_year_range`

### `Tests\Feature\UserProfileControllerTest`

1. `test_update_email_requires_authentication`
2. `test_authenticated_user_can_update_email_with_correct_password`
3. `test_update_email_fails_with_incorrect_current_password`
4. `test_update_email_fails_with_invalid_email_format`
5. `test_update_email_fails_when_email_is_already_taken`
6. `test_authenticated_user_can_update_password_with_correct_current_password`
7. `test_update_password_fails_with_incorrect_current_password`
8. `test_update_password_requires_confirmation_match`
9. `test_update_password_validation_fails_when_new_password_too_short`

### `Tests\Feature\ExampleTest`

1. `test_the_application_returns_a_successful_response`


## `frontend`

## `company-detail.spec.js`

1. `test_ha_a_cegnek_nincs_weboldala_akkor_a_Weboldal_gomb_nem_jelenik_meg_a_kartyán`

2.`test_ha_a_cegnek_nincs_weboldala_akkor_a_reszletes_oldalon_sem_jelenik_meg_a_kulso_weboldal_gomb`

3.`test_ha_a_cegnek_van_weboldala_akkor_a_reszletes_oldalon_megjelenik_a_kulso_weboldal_gomb`

4.`test_nem_letezo_tuning_ceg_eseten_hibauzenet_jelenik_meg`

## `product-detail.spec.js`
1.`test_termékkártyára_kattintva_átvisz_a_részletes_oldalra`
2.`test_részletes_oldalon_megjelennek_a_termék_adatai`
3.`test_termék_kosárba_rakható_a_részletes_oldalról`
4.`test_kosárba_rakás_után_a_kosár_oldalon_is_látszik_a_termék`

## `tuning-companies.spec.js`
1. `test_Mutass_mindent_gomb_átvisz_a_tuning_cégek_oldalra`
2. `test_a_cégek_megjelennek_API_mockból`
3. `test_mindegyik_mockolt_céghez_megjelenik_az_Adatlap_gomb`
4. `test_Adatlap_gomb_átvisz_a_company_detail_oldalra`
4. `test_külső_weboldal_gomb_látszik_a_céges_kártyán`
4. `test_külső_weboldal_gomb_látszik_a_részletes_oldalon_is`


## `cart-spec.js`
1. `test_üres_kosár_esetén_megjelenik_az_üres_kosár_üzenet`
2. `test_mennyiség_növelése_és_csökkentése_működik`
3. `test_termék_törlése_után_megfelelően_frissül_a_kosár`
4. `test_több_termék_esetén_az_összesítő_helyesen_működik`
5. `test_a_pénztár_gomb_akkor_is_kattintható_ha_van_termék`

## Összegzés

- Backend tesztkör sikeresen lefutott.
- A tesztek a guards, authentikáció/felhasználókezelés, valamint márka/modell funkciókat lefedik.
- A jegyzőkönyv a futtatott tesztek teljes listáját és az összesített eredményeket tartalmazza.

---------------------------------------------------------------------------------------------------------------------------------