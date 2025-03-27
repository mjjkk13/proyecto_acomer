const nodemailer = require('nodemailer');
const path = require('path');
require('dotenv').config({ 
  path: "C:/xampp/htdocs/proyecto_acomer/server/node/.env" // Ruta absoluta de tu .env
});
const { createLogger, format, transports } = require('winston');

// Configurar logger
const logger = createLogger({
  level: process.env.NODE_ENV === 'production' ? 'info' : 'debug',
  format: format.combine(
    format.timestamp(),
    format.errors({ stack: true }),
    format.json()
  ),
  transports: [new transports.Console()]
});

// Validación básica de variables de entorno
const validateEnv = () => {
  const requiredVars = ['EMAIL_USER', 'EMAIL_PASSWORD'];
  requiredVars.forEach(varName => {
    if (!process.env[varName]) {
      logger.error(`Falta la variable de entorno: ${varName}`);
      process.exit(1);
    }
  });
};
validateEnv();

// Configuración segura del transporter
const transporter = nodemailer.createTransport({
  service: 'gmail',
  host: process.env.SMTP_HOST || 'smtp.gmail.com',
  port: Number(process.env.SMTP_PORT) || 465,
  secure: true,
  auth: {
    user: process.env.EMAIL_USER,
    pass: process.env.EMAIL_PASSWORD
  },
  connectionTimeout: 10000, // 10 segundos
  pool: true,
  maxConnections: 5
});

// Validar dirección de correo electrónico
const isValidEmail = email => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
};

// Plantilla HTML separada
const createCredentialsTemplate = (usuario, contrasena) => `
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <style>
    .container { font-family: Arial; max-width: 600px; color: #1f2937; }
    .header { color: #2563eb; }
    .credentials { background-color: #f3f4f6; padding: 1rem; border-radius: 0.5rem; }
    .warning { color: #dc2626; font-size: 0.875rem; }
  </style>
</head>
<body>
  <div class="container">
    <h2 class="header">¡Bienvenido a Acomer!</h2>
    <p>Tus credenciales de acceso:</p>
    <div class="credentials">
      <p><strong>Usuario:</strong> ${usuario}</p>
      <p><strong>Contraseña temporal:</strong> ${contrasena}</p>
    </div>
    <p class="warning">⚠️ Por seguridad, cambia tu contraseña inmediatamente después del primer acceso.</p>
  </div>
</body>
</html>
`;

const enviarCredenciales = async (destinatario, usuario, contrasena) => {
  try {
    if (!isValidEmail(destinatario)) {
      throw new Error('Formato de correo electrónico inválido');
    }

    const mailOptions = {
      from: `"Sistema Acomer" <${process.env.EMAIL_USER}>`,
      to: destinatario,
      replyTo: process.env.EMAIL_SUPPORT || 'soporte@acomer.com',
      subject: 'Credenciales de Acceso - Sistema Acomer',
      html: createCredentialsTemplate(usuario, contrasena),
      priority: 'high'
    };

    const info = await transporter.sendMail(mailOptions);
    
    logger.info('Correo enviado exitosamente', {
      messageId: info.messageId,
      destinatario,
      enviadoEl: new Date().toISOString()
    });
    
    return { success: true, messageId: info.messageId };

  } catch (error) {
    logger.error('Error enviando credenciales', {
      error: error.message,
      stack: error.stack,
      destinatario,
      usuario
    });
    
    return {
      success: false,
      error: process.env.NODE_ENV === 'production' 
        ? 'Error al enviar el correo' 
        : error.message
    };
  }
};

// Verificar conexión SMTP al iniciar
transporter.verify(error => {
  if (error) {
    logger.error('Error de conexión SMTP:', error);
  } else {
    logger.info('Conexión SMTP configurada correctamente');
  }
});

module.exports = { enviarCredenciales };