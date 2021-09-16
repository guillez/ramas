-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: mgn_encuesta_externa_log_envio
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP TABLE IF EXISTS mgn_encuesta_externa_log_envio;
CREATE  TABLE mgn_encuesta_externa_log_envio
(
	estado Char(1) NOT NULL,
	fecha_envio Date NOT NULL,
	hora_envio Time NOT NULL,
	log Varchar(250)
);


-- ++++++++++++++++++++++++++ Fin tabla mgn_encuesta_externa_log_envio +++++++++++++++++++++++++++++

