const express = require('express');
const router = express.Router();
const nodemailer = require('nodemailer');
require('dotenv').config();

const transporter = nodemailer.createTransport({
  service: 'Gmail',
  auth: {
    user: process.env.EMAIL_USER,
    pass: process.env.EMAIL_PASS
  }
});

router.post('/enviar-credenciales', async (req, res) => {
  try {
    const { destinatario, usuario, contrasena } = req.body;

    // Validación básica
    if (!destinatario || !usuario || !contrasena) {
      return res.status(400).json({ 
        success: false,
        message: 'Faltan campos requeridos' 
      });
    }

    const info = await transporter.sendMail({
      from: `"A comer" <${process.env.EMAIL_USER}>`,
      to: destinatario,
      subject: 'Credenciales de Acceso',
      html: `
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
          <h2 style="color: #2563eb;">Bienvenido al Sistema</h2>
          <p>Aquí están tus credenciales de acceso:</p>
          <div style="background: #f3f4f6; padding: 16px; border-radius: 8px; margin: 16px 0;">
            <p><strong>Usuario:</strong> ${usuario}</p>
            <p><strong>Contraseña:</strong> ${contrasena}</p>
          </div>
          <p>Por favor, cambia tu contraseña después del primer inicio de sesión.</p>
        </div>
      `
    });

    res.json({ 
      success: true,
      message: 'Correo enviado exitosamente',
      info: info.messageId
    });
  } catch (error) {
    console.error('Error enviando correo:', error);
    res.status(500).json({ 
      success: false,
      message: 'Error al enviar el correo',
      error: error.toString()
    });
  }
});

module.exports = router;