<?php
/*
= LuxCal event calendar language file =

La traduction française a été réalisée par Fabiou (fabiou.dec@gmail.com).

© Copyright 2009-2013  Issam - www.Issam.eu

This file is part of the LuxCal Web Calendar.
*/

//LuxCal ui language file version
define("LUI","3.1.2");

/* -- Titles on the Header of the Calendar -- */

$months = array("Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
$months_m = array("Jan","Fév","Mar","Avr","Mai","Jui","Jui","Aoû","Sep","Oct","Nov","Déc");
$wkDays = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche");
$wkDays_l = array("Dim","Lun","Mar","Mer","Jeu","Ven","Sam","Dim");
$wkDays_m = array("Di","Lu","Ma","Me","Je","Ve","Sa","Di");
$wkDays_s = array("D","L","M","M","J","V","S","D");


/* -- User Interface texts -- */

$xx = array(
"set_event_student_name" => "nom de l'élève",
"set_event_student_telephone" => "élève téléphone",
//general
"submit" => "Soumettre",
"none" => "Aucun.",
"back" => "Retour",
"by" => "par",
"of" => "ou",
"done" => "Done",
"at_time" => "@", //date and time separator (e.g. 11-09-2012 @ 10:45)
"no_way" => "You are not authorized to perform this action",

//index.php
"title_upcoming" => "Prochains événements",
"title_event" => "Événement",
"title_add_event" => "Ajouter un Événement",
"title_check_event" => "Cocher l'Evénement",
"title_log_in" => "Ouverture de session",
"title_search" => "Rechercher du texte",
"title_user_guide" => "Guide de l'utilisateur",
"title_settings" => "Gestion des paramètres",
"title_edit_cats" => "Gestion des catégories",
"title_edit_users" => "Gestion des utilisateurs",
"title_manage_db" => "Gérer la base de données",
"title_changes" => "Événements Ajoutés / Modifiés / Supprimés",
"title_csv_import" => "Importer un fichier CSV",
"title_ics_import" => "Importer un fichier iCalendar",
"title_ics_export" => "Exporter un fichier iCalendar",
"idx_log_in" => "Connexion",
"idx_public_name" => "Accès Public",

//header.php
"hdr_button_back" => "Back to parent page",
"hdr_button_options" => "Options",
"hdr_options_submit" => "Faites votre choix puis appuyer sur 'Done'",
"hdr_options_panel" => "Menu Options",
"hdr_select_date" => "Aller à la date",
"hdr_view" => "Vue",
"hdr_select_view" => "Choisir la vue",
"hdr_filter" => "Filtre",
"hdr_select_filter" => "Choisir le filtre",
"hdr_lang" => "Langue",
"hdr_select_lang" => "Choisir la langue",
"hdr_all_cats" => "Catégories: Toutes",
"hdr_all_users" => "Utilisateurs: Tous",
"hdr_year" => "Année",
"hdr_month_full" => "Mois (7d)",
"hdr_month_work" => "Mois de travail ",
"hdr_week_full" => "Semaine (7j)",
"hdr_week_work" => "Semaine de travail",
"hdr_day" => "Jour",
"hdr_upcoming" => "A venir",
"hdr_changes" => "Modifications",
"hdr_select_admin_functions" => "Choisir la fonction Admin",
"hdr_admin" => "Menu admin",
"hdr_settings" => "Paramètres",
"hdr_categories" => "Catégories",
"hdr_users" => "Utilisateurs",
"hdr_database" => "Base de données",
"hdr_import_csv" => "Import CSV",
"hdr_import_ics" => "Import iCal",
"hdr_export_ics" => "Export iCal",
"hdr_calendar" => "Retourner dans le calendrier",
"hdr_button_print" => "Imprimer",
"hdr_print_page" => "Imprimez cette page",
"hdr_button_todo" => "A faire",
"hdr_todo_list" => "A faire",
"hdr_button_upco" => "Prochain",
"hdr_upco_list" => "Prochains événements",
"hdr_button_search" => "Rechercher",
"hdr_search" => "Rechercher texte",
"hdr_button_add" => "Ajouter",
"hdr_add_event" => "Ajout événement",
"hdr_button_help" => "Aide",
"hdr_help" => "Manuel d'utilisation",
"hdr_button_log_in" => "Connexion",
"hdr_button_log_out" => "Déconnexion",
"hdr_today" => "Aujourd'hui", //dtpicker.js
"hdr_clear" => "Effacer", //dtpicker.js

//header_s.php
"hdr_close_window" => "Fermer la fenêtre",

//event.php
"evt_no_title" => "Titre manquant",
"evt_no_start_date" => "Date de début manquante",
"evt_bad_date" => "Mauvaise date",
"evt_bad_rdate" => "Mauvaise date de fin de répétition",
"evt_no_start_time" => "Heure du début manquante",
"evt_bad_time" => "Mauvaise heure",
"evt_end_before_start_time" => "Heure de fin précède heure de début",
"evt_end_before_start_date" => "Date de fin précède date de début",
"evt_until_before_start_date" => "Fin de répétition précède date de début",
"evt_approved" => "Event approved",
"evt_apd_locked" => "Event approved and locked",
"evt_title" => "Titre",
"evt_venue" => "Lieu",
"evt_category" => "Catégorie",
"evt_description" => "Description",
"evt_date_time" => "Date / heure",
"evt_mailer" => "notification",
"evt_private" => "Privé",
"evt_start_date" => "Date début",
"evt_end_date" => "Date fin",
"evt_select_date" => "Choisir la date",
"evt_select_time" => "Choisir l'heure",
"evt_all_day" => "Journée entière",
"evt_change" => "Modifier",
"evt_set_repeat" => "Répétition",
"evt_set" => "OK",
"evt_help" => "help",
"evt_repeat_not_supported" => "Répétition spécifiée non supportée",
"evt_no_repeat" => "Pas de répétition",
"evt_repeat" => "Répète",
"evt_repeat_on" => "Répète chaque ",
"evt_until" => "jusqu'à",
"evt_blank_no_end" => "vide: illimité",
"evt_each_month" => "chaque mois",
"evt_interval1_1" => "chaque",
"evt_interval1_2" => "chaque deuxième",
"evt_interval1_3" => "chaque troisième",
"evt_interval1_4" => "chaque quatrième",
"evt_interval1_5" => "chaque cinquième",
"evt_interval1_6" => "chaque sixième",
"evt_interval2_1" => "premier",
"evt_interval2_2" => "deuxième",
"evt_interval2_3" => "troisième",
"evt_interval2_4" => "quatrième",
"evt_interval2_5" => "dernier",
"evt_period1_1" => "jour",
"evt_period1_2" => "semaine",
"evt_period1_3" => "mois",
"evt_period1_4" => "année",
"evt_notify" => "Notification",
"evt_now_and_or" => "maintenant et/ou",
"evt_event_added" => "Evénement ajouté",
"evt_event_edited" => "Evénement modifié",
"evt_event_deleted" => "Evénement supprimé",
"evt_days_before_event" => " jour(s) avant l'événement à :",
"evt_mail_help" => "Email reminders can be sent directly (now) or the specified number of days before the start of the event. In the field below, recipient mail addresses and/or names of files with recipient mail addresses can be specified. Each address/file name should be séparée par un point-virgule. Longueur du champ 255 caractères max. Files with email addresses with one email address per line should be located in the folder \'emlists\'.<br>When omitted, the default file name extension of a file with email addresses is .txt.",
"evt_eml_list_too_long" => "La liste des adresses email a trop de caractère.",
"evt_eml_list_missing" => "Adresse(s) email manquante(s)",
"evt_not_in_past" => "Date de notification dépassée",
"evt_not_days_invalid" => "Jour de notification invalide",
"evt_status" => "Status",
"evt_descr_help" => "The following items can be included in the description field ...<br>- HTML tags &lt;b&gt;, &lt;i&gt;, &lt;u&gt; and &lt;s&gt; for bold, italic, underlined and striked-through text.<br>- lien URL in the following format: url ou url [nom], where \'nom\' will be the title of the link. Ex. www.google.fr [chercher].<br>- small images (thumbnails) in the following format: folder/image_name.ext or image_name.ext. When omitted, the default folder is \'thumbnails\'. The folder must be a subfolder of the calendar and the extension must be .gif, .jpg or .png. The thumbnail (image) files should be uploaded via FTP.",
"evt_confirm_added" => "événement ajouté",
"evt_confirm_saved" => "événement sauvé",
"evt_confirm_deleted" => "événement supprimé",
"evt_add_close" => "Ajouter et fermer",
"evt_add" => "Ajouter",
"evt_edit" => "Modifier",
"evt_save_close" => "Sauver et fermer",
"evt_save" => "Sauver",
"evt_clone" => "Dupliquer",
"evt_delete" => "Supprimer",
"evt_close" => "Fermer",
"evt_open_calendar" => "Ouvrir calendrier",
"evt_added" => "Ajouté",
"evt_edited" => "Edité",
"evt_is_repeating" => "est un événement répèté.",
"evt_is_multiday" => "est un événement multi-jours.",
"evt_edit_series_or_occurrence" => "Voulez-vous éditer la série ou cette occurence ?",
"evt_edit_series" => "Editer la série",
"evt_edit_occurrence" => "Editer l'occurrence",
"evt_no_end_date" => "aucune date de fin",

//views
"vws_add_event" => "Ajout nouvel événement",
"vws_view_month" => "Mois",
"vws_view_week" => "Semaine",
"vws_view_day" => "Jour",
"vws_click_for_full" => "Cliquer sur le mois pour afficher le calendrier",
"vws_view_full" => "Voir le calendrier complet",
"vws_prev_month" => "Mois précédent",
"vws_next_month" => "Mois suivant",
"vws_today" => "Aujourd'hui",
"vws_back_to_today" => "Retour au mois courant",
"vws_week" => "Semaine",
"vws_wk" => "Sem",
"vws_time" => "Heure",
"vws_events" => "Evénements",
"vws_all_day" => "Journée entière",
"vws_earlier" => "Plus tôt",
"vws_later" => "Plus tard",
"vws_venue" => "Lieu",
"vws_events_for_next" => "Evénements pour les prochains",
"vws_days" => "jours",
"vws_added" => "Ajouté",
"vws_edited" => "Modifié",
"vws_notify" => "Envoi d'une notification",
"vws_check_mark" => "Check mark",
"vws_none_due_in" => "Pas d'événements pour les prochains",

//changes.php
"chg_from_date" => "A partir de",
"chg_select_date" => "Choisir date de début",
"chg_notify" => "Notification",
"chg_days" => "jour(s)",
"chg_added" => "Ajouté",
"chg_edited" => "Modifié",
"chg_deleted" => "Supprimé",
"chg_changed_on" => "Modifié le",
"chg_changes" => "Modifications",
"chg_no_changes" => "Aucune modification.",

//search.php
"sch_define_search" => "Texte à rechercher",
"sch_search_text" => "Rechercher texte",
"sch_event_fields" => "Champs de l'événement",
"sch_all_fields" => "Tous les champs",
"sch_title" => "Titre",
"sch_description" => "Description",
"sch_venue" => "Lieu",
"sch_event_cat" => "Catégorie de l'événement",
"sch_event_emp" => "Employe de l'événement",
"sch_event_circuit" => "Circuit événement",
"sch_all_cats" => "Toutes les catégories",
"event_employe_name" => "employé",
"sch_all_emps" => "Tous les employés",
"sch_all_circuits" => "Tous les circuits",

"sch_occurring_between" => "Occurrence entre",
"sch_select_start_date" => "Sélectionner la date de début",
"sch_select_end_date" => "Sélectionner la date de fin",
"sch_search" => "Recherche",
"sch_invalid_search_text" => "Texte à rechercher invalide ou trop court",
"sch_bad_start_date" => "Mauvaise date de début",
"sch_bad_end_date" => "Mauvaise date de fin",
"sch_no_results" => "Pas de résultat pour la recherche",
"sch_new_search" => "Nouvel recherche",
"sch_calendar" => "Retour au calendrier",
"sch_instructions" =>
"<h4>Instructions pour Rechercher du texte</h4>
<p>La base de données du calendrier peut être utilisée pour rechercher un texte spécifique.</p>
<br><p><b>Rechercher du texte</b>: Le texte sera recherché dans tous les champs sélectionnés
(voir ci-dessous). La recherche est insensible à la casse (majuscule ou miniscule).</p>
<p>Deux caractères spéciaux peuvent être utilisés:</p>
<ul>
<li>Le caractère Underscore (_) permet de remplacer un ou deux caractère(s) dans le texte à rechercher.
Le résultat comportera le nombre de lettres exact dans le texte avec des nuances en fonction de l'emplacement
du (_).<br>
Ex.: '_e_r' enverra le résultat 'beer', 'dear', 'heir'.</li>
<li>Le caractère 'Et' Commercial (&amp;) permet de remplacer une série de caractère dans le texte.<br>
Ex.: 'dé&amp;r' enverra le résultat 'Décembre', 'dépendance', 'développer'.</li>
</ul>
<p>Cepandant, le texte à rechercher doit au moins contenir deux autres caractères.</p>
<br><p><b>Champs de l'événement</b>: La recherche s'effectue uniquement dans les champs sélectionnés.</p>
<br><p><b>Catégorie de l'événement</b>: La recherche s'effectue uniquement sur la catégorie sélectionnée.</p>
<br><p><b>Occurrence entre</b>: La date de début et la date de fin sont facultatives. Si la date de début est vide,
la recherche s'effectuera sur un an précédent la date du jour. Si la date de fin est vide, la recherche s'effectuera
sur un an après la date du jour.</p>
<br><p>L'affichage du résultat de la recherche sera en ordre chronologique.</p>",

//stand-alone sidebar (lcsbar.php)
"ssb_upco_events" => "Prochains événements",
"ssb_all_day" => "Journée entière",
"ssb_none" => "Pas d'événements."
);
?>
