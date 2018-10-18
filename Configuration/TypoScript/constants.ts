plugin.tx_fpnewsletter_pi1 {
  view {
    # cat=plugin.tx_fpnewsletter_pi1/file; type=string; label=Path to template root (FE)
    templateRootPath = EXT:fp_newsletter/Resources/Private/Templates/
    # cat=plugin.tx_fpnewsletter_pi1/file; type=string; label=Path to template partials (FE)
    partialRootPath = EXT:fp_newsletter/Resources/Private/Partials/
    # cat=plugin.tx_fpnewsletter_pi1/file; type=string; label=Path to template layouts (FE)
    layoutRootPath = EXT:fp_newsletter/Resources/Private/Layouts/
  }
  persistence {
    # cat=plugin.tx_fpnewsletter_pi1//a; type=string; label=Default storage PID
    storagePid =
  }
}