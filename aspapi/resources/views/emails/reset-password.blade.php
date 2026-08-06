<!DOCTYPE html>
<html>
<body style="font-family: 'DM Sans', Arial, sans-serif; background:#F8FAFC; padding:2rem; margin:0;">
    <div style="max-width:480px;margin:0 auto;background:#fff;border-radius:8px;overflow:hidden;">
        <div style="background:#1A2A3A;padding:1.5rem;text-align:center;">
            <span style="color:#fff;font-family:'DM Serif Display',serif;font-size:1.25rem;">ASPAPI</span>
        </div>
        <div style="padding:2rem;">
            <p style="color:#1A2A3A;font-size:0.95rem;">Halo {{ $name }},</p>
            <p style="color:#4A6580;font-size:0.875rem;line-height:1.7;">
                Kami menerima permintaan untuk reset password akun ASPAPI Anda. Klik tombol di bawah untuk membuat password baru. Link ini berlaku selama 60 menit.
            </p>
            <div style="text-align:center;margin:2rem 0;">
                <a href="{{ $resetUrl }}"
                    style="background:#2A7FC1;color:#fff;padding:0.875rem 2rem;border-radius:4px;text-decoration:none;font-size:0.8rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;">
                    Reset Password
                </a>
            </div>
            <p style="color:#8CA9BE;font-size:0.75rem;line-height:1.6;">
                Jika Anda tidak meminta reset password, abaikan email ini — password Anda tidak akan berubah.
            </p>
        </div>
    </div>
</body>
</html>