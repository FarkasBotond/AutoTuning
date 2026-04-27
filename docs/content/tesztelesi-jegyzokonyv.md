# Tesztelési jegyzőkönyv

## Projekt és környezet

- Projekt: AutoTuning
- Dátum: 2026-04-27
- Backend: Laravel Feature testek (`php artisan test`)
- Frontend: Playwright E2E testek Docker konténerben

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

- Eredmény: **PASS**
- Összesített kimenet: **6 passed**

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

## Frontend E2E tesztesetek (minden egyes teszt)

### `frontend/tests/e2e/admin-catalog.spec.js`

1. `admin can view brands list page`
2. `admin can view models list page`

### `frontend/tests/e2e/auth-flow.spec.js`

1. `user can login and gets redirected to home`

### `frontend/tests/e2e/auth-guards.spec.js`

1. `unauthenticated user is redirected to login from admin page`
2. `non-admin user is redirected to home from admin page`
3. `authenticated user can open profile page`

## Összegzés

- Backend és frontend tesztkör sikeresen lefutott.
- A tesztek a guards, authentikáció/felhasználókezelés, valamint márka/modell funkciókat lefedik.
- A jegyzőkönyv a futtatott tesztek teljes listáját és az összesített eredményeket tartalmazza.
