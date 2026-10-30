<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — JWP Coffee</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'DM Sans',sans-serif;background:#2C1A0E;display:flex;align-items:center;justify-content:center;min-height:100vh}
.login-card{background:#fff;border-radius:18px;padding:40px 36px;width:380px;max-width:90%;box-shadow:0 20px 60px rgba(0,0,0,0.3)}
.login-logo{text-align:center;margin-bottom:28px}
.login-logo h1{font-family:'Playfair Display',serif;font-size:26px;color:#4A2C1A;margin-bottom:4px}
.login-logo span{font-size:11px;letter-spacing:2px;text-transform:uppercase;color:#9A8070}
.form-group{margin-bottom:16px}
.form-group label{display:block;font-size:11px;font-weight:500;text-transform:uppercase;letter-spacing:0.8px;color:#8B5E3C;margin-bottom:6px}
.form-control{width:100%;padding:10px 14px;border:1.5px solid #F2EDE4;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:13px;color:#2C1A0E;background:#FAF7F2;outline:none;transition:border-color 0.2s}
.form-control:focus{border-color:#C8A97E;background:#fff}
.login-btn{width:100%;padding:11px;background:#8B5E3C;color:#fff;border:none;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:14px;font-weight:500;cursor:pointer;transition:background 0.2s;margin-top:8px}
.login-btn:hover{background:#4A2C1A}
.login-hint{font-size:11px;color:#9A8070;text-align:center;margin-top:16px}
.login-error{background:#FFF0EE;color:#E53935;border:1px solid #FFCCBB;border-radius:8px;padding:9px 14px;font-size:12px;margin-bottom:14px}
.input-pw-wrap{position:relative}
.input-pw-wrap input{padding-right:36px}
.input-pw-wrap .eye-btn{
    position:absolute;
    right:10px;
    top:50%;
    transform:translateY(-50%);
    background:none;
    border:none;
    cursor:pointer;
    color:#C8A97E;
    font-size:13px;
    display:none;
}
</style>
</head>
<body>
<div class="login-card">
  <div class="login-logo">
    <div style="font-size:36px;margin-bottom:6px">☕</div>
    <h1>JWP Coffee</h1>
    <span>Inventory System</span>
  </div>

  @if($errors->has('email'))
    <div class="login-error">{{ $errors->first('email') }}</div>
  @endif

  <form method="POST" action="{{ route('login.post') }}">
    @csrf
    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" class="form-control"
          placeholder="Masukkan email"
          value="{{ old('email') }}">
    </div>
    <div class="form-group">
      <label>Password</label>
      <div class="input-pw-wrap">
        <input type="password"
            name="password"
            class="form-control"
            id="loginPass"
            placeholder="Masukkan password"
            oninput="toggleEyeButton()">

        <button class="eye-btn"
                id="eyeBtn"
                type="button"
                onclick="togglePw()">
            <i class="fa-solid fa-eye" id="eyeIcon"></i>
        </button>
      </div>
    </div>
    <button type="submit" class="login-btn">
      <i class="fa-solid fa-right-to-bracket"></i> Masuk
    </button>
  </form>

  <div style="margin-top:16px;padding:12px;background:#FAF7F2;border:1px dashed #C8A97E;border-radius:10px;text-align:left;">
    <div style="font-size:11px;font-weight:700;color:#8B5E3C;text-transform:uppercase;margin-bottom:6px;">Akun Demo Live:</div>
    <div style="font-size:12px;color:#4A2C1A;display:flex;justify-content:space-between;margin-bottom:4px;">
      <span><strong>Admin:</strong> admin@gmail.com</span>
      <span style="color:#8B5E3C;cursor:pointer;text-decoration:underline;" onclick="fillDemo('admin@gmail.com','admin123')">Pakai</span>
    </div>
    <div style="font-size:12px;color:#4A2C1A;display:flex;justify-content:space-between;">
      <span><strong>Pimpinan:</strong> pimpinan@gmail.com</span>
      <span style="color:#8B5E3C;cursor:pointer;text-decoration:underline;" onclick="fillDemo('pimpinan@gmail.com','pimpinan123')">Pakai</span>
    </div>
    <div style="font-size:11px;color:#9A8070;margin-top:6px;">Password: <code>admin123</code> / <code>pimpinan123</code></div>
  </div>

  <div style="text-align:center;margin-top:16px;display:flex;flex-direction:column;gap:8px;">
    <a href="{{ route('portal') }}" style="font-size:12px;font-weight:600;color:#8B5E3C;text-decoration:none;">&larr; Kembali ke Portal JWP Coffee</a>
    <a href="https://wahyuidaya.github.io" style="font-size:11px;color:#9A8070;text-decoration:none;">&larr; Portofolio Wahyu Idaya</a>
  </div>
</div>
<script>
window.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    const demo = params.get('demo');
    if (demo === 'admin') {
        fillDemo('admin@gmail.com', 'admin123');
    } else if (demo === 'pimpinan') {
        fillDemo('pimpinan@gmail.com', 'pimpinan123');
    }
});
function fillDemo(email, pass){
    document.querySelector('input[name="email"]').value = email;
    const pw = document.getElementById('loginPass');
    pw.value = pass;
    toggleEyeButton();
}

function toggleEyeButton(){
    const inp = document.getElementById('loginPass');
    const eyeBtn = document.getElementById('eyeBtn');

    if(inp.value.trim() !== ''){
        eyeBtn.style.display = 'block';
    }else{
        eyeBtn.style.display = 'none';
        inp.type = 'password';
        document.getElementById('eyeIcon').className = 'fa-solid fa-eye';
    }
}

function togglePw(){
    const inp = document.getElementById('loginPass');
    const ico = document.getElementById('eyeIcon');

    if(inp.type === 'password'){
        inp.type = 'text';
        ico.className = 'fa-solid fa-eye-slash';
    }else{
        inp.type = 'password';
        ico.className = 'fa-solid fa-eye';
    }
}
</script>
</body>
</html>
