.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _introduction:

Einleitung
==========


.. _what-it-does:

Was macht die Extension?
------------------------

Die Extension fp_newsletter wurde dazu geschrieben, eine datenschutzkonforme An- und Abmeldung zu Newslettern zu ermöglichen.
Bisher wird dazu nur die Tabelle tt_address benutzt. Die kann z.B. von der Extension direct_mail zur Newsletter-Versendung benutzt werden.
Sämtliche Aktionen werden in einer Log-Tabelle festgehalten, damit alle Aktionen der Benutzer überprüft werden können.
Allerdings werden manche Einträge auch geändert und nicht immer neu angelegt.
So kann man als Admin sehen, wer sich wann an- oder abgemeldet hat. Erst nach einer erfolgreichen Anmeldung werden die Daten in die
tt_address-Tabelle kopiert. Die Abmeldung findet z.Z. sofort statt, aber eine Double-Opt-Out-Abmeldung ist auch noch in Planung.
Bei der Double-Opt-In-Anmeldung und ggf. auch bei der Abmeldung muss man den Datenschutzbestimmungen zustimmen.


.. _screenshots:

Screenshots
-----------

Einfache Ansicht des Anmelde-Formulars im FE:

.. figure:: ../../Images/frontend.png
   :width: 814px
   :alt: Frontend page

   Anmelde-Formular.

Danke an ...
^^^^^^^^^^^^

Diese Extension wurde programmiert von der
`fixpunkt werbeagentur gmbh, Bonn <https://www.fixpunkt.com/webentwicklung/typo3/typo3-extensions/>`_
und fixpunkt stellt sie der TYPO3-Community zur Verfügung.
