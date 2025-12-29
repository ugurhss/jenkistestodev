<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="x-apple-disable-message-reformatting">
  <title>Hesap Doğrulama Bildirimi</title>

  <style>
    @media screen and (max-width: 520px) {
      .container { width: 100% !important; }
      .px { padding-left: 16px !important; padding-right: 16px !important; }
      .py { padding-top: 18px !important; padding-bottom: 18px !important; }
      .title { font-size: 20px !important; }
      .subtitle { font-size: 14px !important; }
      .btn { display:block !important; width:100% !important; box-sizing:border-box !important; text-align:center !important; }
      .btn-gap { height: 10px !important; width: 100% !important; }
      .stack { display:block !important; width:100% !important; }
      .hide-mobile { display:none !important; }
      .align-right-mobile { text-align:left !important; padding-top:10px !important; }
    }
  </style>
</head>

<body style="margin:0; padding:0; background-color:#0b1220; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Arial,Helvetica,sans-serif;">

  <!-- Preheader -->
  <div style="display:none; max-height:0; overflow:hidden; opacity:0; color:transparent;">
    Hesap doğrulama işleminiz başarıyla tamamlanmıştır.
  </div>

  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
         style="background-color:#0b1220; padding:22px 10px;">
    <tr>
      <td align="center">

        <!-- Container -->
        <table role="presentation" width="640" cellpadding="0" cellspacing="0" border="0"
               class="container"
               style="width:640px; max-width:640px;">

          <!-- top row -->
          <tr>
            <td style="padding:0 6px 12px 6px;">
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td align="left" style="color:#94a3b8; font-size:13px;">
                    <span style="display:inline-block; padding:6px 10px; border:1px solid rgba(148,163,184,0.25); border-radius:999px;">
                      YTM • Resmî Bildirim
                    </span>
                  </td>
                  <td align="right" class="hide-mobile" style="color:#94a3b8; font-size:13px;">
                    {{ date('d.m.Y') }}
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Card -->
          <tr>
            <td style="background:#ffffff; border-radius:20px; overflow:hidden; box-shadow:0 18px 55px rgba(0,0,0,0.35);">

              <!-- Header -->
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td class="px py"
                      style="padding:22px 24px; background:linear-gradient(135deg,#1f2937 0%,#111827 55%,#0f172a 100%);">

                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td align="left" class="stack" style="vertical-align:top;">
                          <div style="display:inline-block; background:rgba(255,255,255,0.10); border:1px solid rgba(255,255,255,0.18); padding:10px 12px; border-radius:14px;">
                            <span style="font-weight:900; color:#ffffff; font-size:14px; letter-spacing:0.6px;">YTM</span>
                            <span style="color:rgba(255,255,255,0.90); font-size:14px; font-weight:700; margin-left:6px;">
                              Hesap Doğrulama
                            </span>
                          </div>
                        </td>

                        <td align="right" class="stack align-right-mobile" style="vertical-align:top;">
                          <div style="display:inline-block; margin-top:10px; padding:8px 12px; border-radius:999px; background:rgba(255,255,255,0.10); border:1px solid rgba(255,255,255,0.18); color:#ffffff; font-size:12px; font-weight:800;">
                            DURUM: BAŞARILI
                          </div>
                        </td>
                      </tr>
                    </table>

                    <h1 class="title" style="margin:16px 0 0 0; color:#ffffff; font-size:24px; line-height:1.25; font-weight:900;">
                      Hesap Doğrulama Bildirimi
                    </h1>
                    <p class="subtitle" style="margin:10px 0 0 0; color:rgba(255,255,255,0.88); font-size:14.5px; line-height:1.7;">
                      E-posta doğrulama işleminiz başarıyla tamamlanmıştır.
                    </p>
                  </td>
                </tr>
              </table>

              <!-- Body -->
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td class="px" style="padding:20px 24px 10px 24px;">

                    <p style="margin:0; color:#0f172a; font-size:15px; line-height:1.85;">
                      Sayın <strong style="color:#111827;">{{ $name }}</strong>,<br>
                      Sistemimize kayıt sırasında tanımlanan e-posta adresiniz doğrulanmış ve hesabınız kullanıma açılmıştır.
                      Bu bildirim, YTM ödevi kapsamındaki doğrulama sürecinin bilgilendirme mesajıdır.
                    </p>

                    <!-- Info box -->
                    <div style="margin-top:14px; padding:14px; border-radius:16px; border:1px solid #e5e7eb; background:#f8fafc;">
                      <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                          <td style="width:6px; background:#111827; border-radius:10px;"></td>
                          <td style="padding-left:12px;">
                            <div style="font-weight:900; color:#111827; font-size:13.5px;">İşlem Bilgisi</div>
                            <div style="color:#475569; font-size:13.5px; line-height:1.65;">
                              <strong>İşlem:</strong> E-posta Doğrulama<br>
                              <strong>Durum:</strong> Başarılı<br>
                              <strong>Tarih:</strong> {{ date('d.m.Y') }}
                            </div>
                          </td>
                        </tr>
                      </table>
                    </div>

                    <!-- Security note -->
                    <div style="margin-top:12px; padding:14px; border-radius:16px; border:1px solid #e5e7eb; background:#ffffff;">
                      <div style="font-weight:900; color:#111827; font-size:13.5px;">Güvenlik Uyarısı</div>
                      <div style="color:#475569; font-size:13.5px; line-height:1.65; margin-top:6px;">
                        Bu işlemi siz gerçekleştirmediyseniz, güvenliğiniz için şifrenizi değiştirmeniz ve destek ekibi ile iletişime geçmeniz önerilir.
                      </div>
                    </div>

                    <!-- Buttons -->
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:16px;">
                      <tr>
                        <td class="stack" style="vertical-align:top;">
                          <a href="{{ $loginUrl ?? '#' }}"
                             class="btn"
                             style="display:inline-block; width:auto; background:#111827; color:#ffffff; text-decoration:none; padding:12px 16px; border-radius:14px; font-size:14px; font-weight:900; letter-spacing:0.2px;">
                            Sisteme Giriş
                          </a>
                        </td>
                        <td class="btn-gap" style="width:10px;"></td>
                        <td class="stack" style="vertical-align:top;">
                          <a href="{{ $helpUrl ?? '#' }}"
                             class="btn"
                             style="display:inline-block; width:auto; background:#ffffff; color:#111827; text-decoration:none; padding:12px 16px; border-radius:14px; font-size:14px; font-weight:900; border:1px solid #e5e7eb;">
                            Destek
                          </a>
                        </td>
                      </tr>
                    </table>

                    <div style="margin:18px 0 0 0; height:1px; background:#e5e7eb;"></div>

                    <p style="margin:12px 0 0 0; color:#64748b; font-size:12.5px; line-height:1.7;">
                      Bu e-posta otomatik olarak oluşturulmuştur. Lütfen yanıtlamayınız.<br>
                      Butonlar çalışmazsa aşağıdaki bağlantıyı tarayıcınızda açınız:
                      <br>
                      <span style="color:#334155; word-break:break-all;">{{ $loginUrl ?? '—' }}</span>
                    </p>

                  </td>
                </tr>

                <!-- Footer -->
                <tr>
                  <td class="px" style="padding:14px 24px 18px 24px; background:#f8fafc;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td class="stack" style="color:#64748b; font-size:12.5px; line-height:1.7;">
                          © {{ date('Y') }} YTM Proje • Bildirim Sistemi
                        </td>
                        <td class="stack" align="right" style="color:#64748b; font-size:12.5px; line-height:1.7;">
                          <a href="mailto:{{ $supportEmail ?? 'support@example.com' }}"
                             style="color:#111827; text-decoration:none; font-weight:900;">
                            {{ $supportEmail ?? 'support@example.com' }}
                          </a>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>

              </table>
            </td>
          </tr>

          <tr><td style="height:14px;"></td></tr>

          <tr>
            <td align="center" style="color:#94a3b8; font-size:12px; line-height:1.6; padding:0 10px;">
              Bu bildirim, YTM ödevi kapsamındaki kayıt/doğrulama sürecinin bilgilendirme mesajıdır.
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>

</body>
</html>
