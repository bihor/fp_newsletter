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
So kann man als Admin sehen, wer sich wann an- oder abgemeldet hat. Erst nach einer erfolgreichen Anmeldung werden die Daten in die
tt_address-Tabelle kopiert. Die Abmeldung findet z.Z. sofort statt, aber eine Double-Opt-Out-Abmeldung ist auch noch in Planung.
Bei der Double-Opt-In-Anmeldung und bei der Abmeldung muss man den Datenschutzbestimmungen zustimmen.

.. Wichtig::

   Dies ist bisher nur eine Beta-Version. Die implementierten Features funktionieren natürlich schon, nur fehlen noch einige optionale Features.


.. _screenshots:

Screenshots
-----------

Einfach Ansicht des Anmelde-Formulars:

.. figure:: ../../Images/frontend.png
   :width: 814px
   :alt: Frontend page

   Anmelde-Formular.
