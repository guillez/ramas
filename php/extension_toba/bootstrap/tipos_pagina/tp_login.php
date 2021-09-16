<?php
namespace ext_bootstrap\tipos_pagina;

class tp_login extends tp_basico
{
    protected $clase_contenido = "col-xs-12 col-ss-8 col-ss-offset-2 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 login-wrapper push";
	/**
	 * Sobreescribo para que haga echo de la cabecera
	 * {@inheritDoc}
	 * @see \ext_bootstrap\tipos_pagina\tp_basico::ingreso_header()
	 */
	function ingreso_header(){
		
		return"<style>
				.footer {
					    background-color: #f5f5f5;
					    /*bottom: 0;*/
					    height: 60px;
					    /*position: absolute;
					    width: 100%;*/
					    border-color: #f5f5f5;
					}
					
				.push {
				       padding-bottom: 60px;
				       
				}
				</style>";
	}

    function pie(){
		echo "
			
			</div> <!-- content-wrapper -->
			<footer class='footer navbar navbar-default navbar-fixed-bottom' style='display: flex; justify-content: center; align-content: center; flex-direction: column;'>
				<div class='col-md-12'>
					<div class='col-md-4 col-md-offset-4 text-center'>
						Desarrollado por <strong><a href='http://www.siu.edu.ar'class='footer_skin' target='_blank'>SIU-CIN</a></strong>
					</div>
					<div class='col-md-4 col-md-offset-4 text-center'>
						2002 - ".date('Y')."
					</div>
				</div>
				
			</footer>
		</body>
		</html>";
	}
	
}