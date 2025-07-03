# 🚗 Showroom Auto – Gestione auto via pannello amministrativo

Sito web responsive per la gestione e visualizzazione di un inventario di auto, completo di interfaccia pubblica e pannello admin con login sicuro. Il progetto permette l'inserimento, modifica, eliminazione e visualizzazione dettagliata delle auto, con immagini multiple e filtri.

---

## 📄 Licenza

Questo progetto è distribuito sotto licenza MIT.  
Puoi usarlo, copiarlo, modificarlo e distribuirlo liberamente, purché venga mantenuto il copyright.

Per i dettagli completi, vedi il file dedicato.

---

## 👤 Autrice

Elektra Marzocchi  
[🌐 elliecreations.it](https://elliecreations.it)  
[📫 info@elliecreations.it](mailto:info@elliecreations.it)  
[💼 LinkedIn](https://www.linkedin.com/in/elektra-marzocchi-11002231a) | [💻 GitHub](https://github.com/EllieCreations)

---

## 📁 Struttura del progetto
```txt
root:
│   .htaccess                   #file configurazione
│   index.php                   #home del sito
│   README.md                   #struttura
│   404.php                     #pagina di errore
│ 
├── .dev-tools/
│   ├── crea_admin.php
│   ├── lista_utenti.php
│   ├── autoshowroom.sql
│   └── reset_password.php  
├───admin
│       add_car.php         # aggiungi auto
│       admin.php           # pannello di amministrazione
│       delete_car.php      # elimina auto
│       edit_car.php        # modifica auto
│       login.php           # pagina di login
│       logout.php          # pagina di logout
│       crea_admin.php      # query per creare un utente
│       lista_utenti.php    # pagina semplice per controllare lista utenti
│       
├───assets
│   ├───images                 # cartella con immagini base per il sito 
│   │       logo.png           # logo del sito
│   │       Simone.svg         # immagine vettoriale Simone
│   │       Simone2.svg        # immagine vettoriale Simone 2
│   │       Simone3.svg        # immagine vettoriale Simone 3
│   │       Simone4.svg        # immagine vettoriale Simone 4
│   │       Simone5.svg        # immagine vettoriale Simone 5
│   │       wallpaper.jpg      # sfondo del hero section
│   │ 
│   ├───temp                   # directory temporanea
│   │       
│   └───uploads                                 # directory per i file caricati
│           img_6788f1ed2c1060.08418791.jpg     # immagine caricata esempio
│           img_6788f1f8d61b82...               # immagine caricata esempio
│           
├───css                     # cartella per i fogli di stile
│       admin.css           # stile per il pannello di amministrazione
│       footer.css          # stile per il footer
│       gallery.css         # stile per la galleria
│       header.css          # stile per l'header
│       home.css            # stile per la home page
│       root.css            # stile globale del sito
│       searchbar.css       # stile per la barra di ricerca
│       styles.css          # stile generale del sito
│       message.php         # stile per i messaggi
│       
├───functions                  #file con funzioni lato server in php
│       car_details.php        # dettagli auto
│       db.php                 # connessione al database
│       filter_car.php         # filtra auto
│       functions.php          # funzioni generali
│       get_car_images.php     # ottieni immagini auto
│       message.php            # gestione messaggi
│       process_contact.php    # processa contatti
│       test_connessione.php   # test connessione
│       
├───includes                # cartella di parti riutilizzabili 
│       footer.php          # footer del sito
│       header.php          # header del sito
│       navbar.php          # barra di navigazione
│       searchbar.php       # barra di ricerca
│       sidebar.php         # barra laterale
│       crediti.html        # pagina dei crediti
│       
├───js                      #script del sito 
│       home.js             # script per la home page
│       script.js           # script generale del sito
│       admin.js            # script per il pannello di amministrazione
│            
├───lightbox2               # cartella per lightbox 2
│       
├───src                     # cartella smtp mail per il form di contatti.php[PHPmailer-master]
│       
└───pages                   # cartella con pagine principali del sito 
        contatti.php        # pagina dei contatti
        details.php         # pagina dei dettagli di un auto
        inventario.php      # pagina dell'inventario di auto

```
---

## 🛠️ Funzionalità principali

- ✅ Aggiunta/modifica/eliminazione auto
- ✅ Caricamento multiplo di immagini (fino a 15)
- ✅ Visualizzazione dettagliata delle auto
- ✅ Filtri dinamici e ordinamento
- ✅ Sistema di login sicuro con password hashata
- ✅ Logout e gestione sessione
- ✅ UI responsive e accessibile
- ✅ Componenti riutilizzabili (header, footer, navbar, sidebar)

---

## 🔐 Accesso area admin

Per accedere all'area amministrativa:

1. Vai su `/admin/login.php`
2. Inserisci username: admin psw : admin . Se il database è vuoto, puoi usare un file temporaneo come `crea_admin.php` per creare un utente.

> Le password sono salvate in modo sicuro tramite `password_hash()`.

---

## ⚙️ Tecnologie usate

- 🐘 PHP 8+
- 🗃️ MySQL / MariaDB
- ✨ HTML5 / CSS3 (custom + responsive)
- 🧠 JavaScript (vanilla)
- 📦 PHPMailer (via `/src/`)
- 🎨 [Lordicon](https://lordicon.com) per animazioni
- 📸 Lightbox 2 (gallery immagini)

---

## 🧠 Database – autoshowroom.sql

Questo progetto utilizza un database relazionale MySQL chiamato `autoshowroom`.

### 📋 Tabelle principali

| Tabella           | Descrizione                                             |
|-------------------|----------------------------------------------------------|
| `admin_users`     | Utenti amministratori con username e password hashata   |
| `cars`            | Tabella principale con i dati delle auto                |
| `brands`          | Elenco delle marche (es. Fiat, BMW, Tesla…)             |
| `car_types`       | Tipi di carrozzeria (es. Berlina, SUV, Roadster)        |
| `fuel_types`      | Tipo carburante (es. benzina, diesel, elettrico…)       |
| `transmissions`   | Tipo di cambio (manuale, automatico, CVT…)              |
| `car_images`      | Galleria immagini collegate alle auto                   |

### 🔗 Relazioni tra tabelle

- `cars.brand_id` → `brands.id`
- `cars.car_type_id` → `car_types.id`
- `cars.fuel_type_id` → `fuel_types.id`
- `cars.transmission_id` → `transmissions.id`
- `car_images.car_id` → `cars.id`

Tutte le foreign key hanno `ON DELETE CASCADE`, quindi se un'auto viene eliminata, anche le sue immagini vengono rimosse.

### 🔐 Account di esempio

| Username | Password   |
|----------|------------|
| `admin`  | `admin123` (hashata in DB con `password_hash`) |

### 📥 Come importare

1. Crea un nuovo database chiamato `autoshowroom`
2. Importa `.dev-tools/autoshowroom.sql` in phpMyAdmin.
