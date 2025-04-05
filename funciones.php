<?php

function mostrarArrayMensajeDeErrores($listaErrores){
  $totalErrores = count($listaErrores);
  $mensajeError = "";
  if($totalErrores > 1){
    $mensajeError .= "<br>"; 
  }
  $mensajeError .= str_replace(".", "", implode(",<br> ", $listaErrores));  
  $mensajeError .=  ".";
  return $mensajeError;
}

?>