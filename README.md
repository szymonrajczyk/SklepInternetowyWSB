# Sklep internetowy WSB

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

## Opis projektu

Sklep Internetowy to aplikacja webowa stworzona w Laravelu, umożliwiająca zarządzanie produktami, użytkownikami oraz procesem zakupowym. System został wyposażony w kontrolę dostępu opartą na rolach i posiada trzy typy zalogowanych użytkowników oraz gościa:

- **Admin**: Zarządzanie użytkownikami, produktami, kategoriami oraz zamówieniami
- **Sprzedający**: Użytkownik wystawiający ogłoszenia
- **Kupujący**: Użytkownik mogący składać zamówienia
- **Gość**: Niezalogowany użytkownik mogący przeglądać ogłoszenia

## Funkcjonalności

- Rejestracja i logowanie z kontrolą ról
- Zarządzanie produktami (dodawanie, edycja, usuwanie)
- Obsługa koszyka (przechowywany w sesji)
- Składanie zamówień przez klientów
- Panel administracyjny z podglądem użytkowników i zamówień
- Walidacja formularzy (np. rejestracja, dodawanie produktów)
- Przypisywanie użytkowników do ról
- Prosty system zarządzania kategoriami produktów

## Wymagania

- PHP 8.1 lub nowsza
- Composer
- MySQL lub inna baza danych wspierana przez Laravel
- Node.js i npm

## Instalacja

### Krok 1: Klonowanie Repozytorium

```bash
git clone <repository-url>
cd [nazwa_folderu]
```

### Krok 2: Instalacja zależności

```bash
composer install
npm install --force
npm run dev
```

### Krok 3: Konfiguracja środowiska

```bash
cp .env.example .env
php artisan key:generate
```

Edytuj plik .env, aby skonfigurować połączenie z bazą danych:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wsb_2024_k07_P9
DB_USERNAME=root
DB_PASSWORD=
```

### Krok 4: Migracja i dane testowe

```bash
php artisan migrate --seed
```

Zostaną utworzone tabele i załadowane dane startowe:
- Domyślni użytkownicy (admin)
- Przykładowe produkty

### Krok 5: Utwórz symlink do katalogu przechowywania

```bash
php artisan storage:link
```

## Uruchomienie aplikacji

```bash
php artisan serve
```

Otwórz przeglądarkę: http://localhost:8000

## Domyślne dane logowania

- **Admin User**:
  - Email: admin@admin.pl
  - Hasło: admin123

## Autorzy

- Szymon Rajczyk
- Hubert Bałuszyński
- Mikołaj Czajkowski

