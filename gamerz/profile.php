<?php
session_start();
require "connection.php"; 

// ✅ user must be logged in
if (!isset($_COOKIE['name'])) {
  header("Location: login.php");
  exit;
}

$uname = $_COOKIE['name'];

// ✅ fetch user profile data
$stmt = $conn->prepare("SELECT * FROM signup WHERE uname = ?");

$stmt->bind_param("s", $uname);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
  die("User not found");
}

$user = $result->fetch_assoc();

// ✅ set final values safely
$name     = $user['uname'];
$location = $user['mail'];
$avatar   = $user['profile_pic'] ? "uploads/" . $user['profile_pic'] : "default_avatar.png";


// ✅ fetch games

?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>GamerLink — Player Profile</title>

<!-- ✅ your CSS remains EXACTLY same -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
/* ✅ ALL YOUR ORIGINAL CSS WITHOUT CHANGE */
:root{
    --bg-1:#06060a;
    --bg-2:#0b0f17;
    --muted:#cfc9ff;
    --neon-purple:#6a1bff;
    --neon-cyan:#00eaff;
    --card-bg: rgba(255,255,255,0.03);
    --glass: rgba(255,255,255,0.02);
    --radius:14px;
  }
  *{box-sizing:border-box;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,"Helvetica Neue",Arial;}
  html,body{height:100%;margin:0;background:
    radial-gradient(1200px 600px at 10% 10%, rgba(58,30,85,0.10), transparent 8%),
    linear-gradient(180deg,var(--bg-1),var(--bg-2)); color:#eae6ff; -webkit-font-smoothing:antialiased;}
  body::before{
    content:"";position:fixed;inset:0;background-image:
    linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
    background-size:40px 40px; opacity:0.06; pointer-events:none; animation:gridMove 18s linear infinite;
  }
  @keyframes gridMove{from{transform:translateY(0)}to{transform:translateY(-40px)}}

  /* page layout */
  .app{display:flex;gap:24px;min-height:100vh;padding:28px;}
  .sidebar{
    width:220px;padding:22px;border-radius:12px;background:linear-gradient(180deg,rgba(255,255,255,0.02),transparent);
    border:1px solid rgba(138,59,255,0.06);backdrop-filter:blur(6px);
  }
  .logo{display:flex;align-items:center;gap:10px;margin-bottom:28px}
  .logo .mark{width:36px;height:36px;border-radius:8px;background:linear-gradient(90deg,var(--neon-purple),var(--neon-cyan));display:inline-block}
  .logo h3{font-size:16px;margin:0}
  .nav{display:flex;flex-direction:column;gap:14px;margin-top:18px}
  .nav button{display:flex;gap:10px;align-items:center;padding:10px;border-radius:10px;background:transparent;border:1px solid rgba(255,255,255,0.02);color:#dcd6ff;cursor:pointer}
  .nav button.active{background:linear-gradient(90deg, rgba(138,59,255,0.14), rgba(0,234,255,0.06));box-shadow:0 8px 30px rgba(138,59,255,0.06)}
  .sidebar .bottom {margin-top:24px;font-size:13px;color:#bfb7ff}

  /* main column */
  .main{flex:1;display:flex;flex-direction:column;gap:18px}
  .topbar{display:flex;align-items:center;justify-content:space-between;gap:12px}
  .search{
    display:flex;align-items:center;gap:12px;padding:10px 14px;border-radius:12px;width:60%;
    background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
    border:1px solid rgba(255,255,255,0.03);
  }
  .search input{background:transparent;border:none;outline:none;color:inherit;width:100%}
  .action-btns{display:flex;gap:12px;align-items:center}

  /* profile header */
  .profile-card{
    display:flex;gap:20px;align-items:end;padding:22px;border-radius:14px;background:linear-gradient(180deg, rgba(255,255,255,0.015), rgba(255,255,255,0.01));
    border:1px solid rgba(255,255,255,0.03);
  }
  .profile-banner{
    flex:1;height:180px;border-radius:12px;background-image:url('/mnt/data/ed56da13-f4b4-4d8a-8962-500df70b4e92.png');
    background-size:cover;background-position:center;position:relative;overflow:hidden;
    display:flex;align-items:flex-end;padding:18px;
  }
  .avatar{
    width:110px;height:110px;border-radius:50%;border:6px solid rgba(0,0,0,0.5);
    box-shadow:0 8px 30px rgba(0,0,0,0.6);background:linear-gradient(90deg,var(--neon-purple),var(--neon-cyan));
    display:flex;align-items:center;justify-content:center;font-weight:700;color:#081018;font-size:22px;margin-right:18px;
  }
  .profile-meta{display:flex;align-items:center;gap:18px}
  .profile-meta .info h2{margin:0;font-size:22px}
  .profile-meta .info p{margin:6px 0 0 0;color:#d1ccff}
  .profile-actions{display:flex;gap:10px;margin-left:auto}
  .btn-primary{background:linear-gradient(90deg,var(--neon-purple),var(--neon-cyan));color:#081018;padding:10px 14px;border-radius:10px;border:none;font-weight:700;cursor:pointer}
  .btn-outline{background:transparent;border:1px solid rgba(255,255,255,0.06);padding:10px 14px;border-radius:10px;color:#e6e2ff;cursor:pointer}

  /* layout columns under header */
  .content{
    display:grid;grid-template-columns:320px 1fr;gap:24px;align-items:start;
  }

  /* left column cards */
  .card{background:var(--card-bg);border-radius:12px;padding:18px;border:1px solid rgba(255,255,255,0.03)}
  .card h4{margin:0 0 12px 0}
  .games .game{display:flex;gap:12px;align-items:center;padding:12px;border-radius:10px;background:linear-gradient(180deg, rgba(255,255,255,0.02), transparent);border:1px solid rgba(255,255,255,0.02);margin-bottom:10px}
  .game .icon{width:44px;height:44px;border-radius:8px;background:#0b0f17;display:grid;place-items:center;border:1px solid rgba(255,255,255,0.03)}
  .skills .chip{display:inline-block;padding:8px 12px;margin:6px 8px 6px 0;border-radius:999px;background:linear-gradient(90deg, rgba(138,59,255,0.12), rgba(0,234,255,0.06));color:#eae6ff}

  /* right column tabs and gallery */
  .tabs{display:flex;gap:18px;border-bottom:1px solid rgba(255,255,255,0.03);padding-bottom:8px;margin-bottom:16px}
  .tabs button{background:transparent;border:none;color:#bfb7ff;padding:8px 6px;cursor:pointer}
  .tabs button.active{color:white;border-bottom:3px solid var(--neon-purple);padding-bottom:5px}
  .gallery{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
  .thumb{border-radius:10px;overflow:hidden;height:110px;background:#0a0a0a;border:1px solid rgba(255,255,255,0.02)}
  .thumb img{width:100%;height:100%;object-fit:cover;display:block}

  /* small info blocks */
  .follow-list li{display:flex;gap:10px;align-items:center;margin-bottom:10px}
  .follow-list li img{width:28px;height:28px;border-radius:6px;object-fit:cover}
  .muted{color:#bfb7ff;font-size:13px}

  /* responsive */
  @media(max-width:980px){
    .app{padding:16px}
    .content{grid-template-columns:1fr; }
    .profile-banner{height:220px}
    .gallery{grid-template-columns:repeat(2,1fr)}
  }
 

</style>

</head>
<body>

<div class="app">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="logo">
      <img class="mark" src="logo/logo.jpg">
      <h3>GamerLink</h3>
    </div>
    <nav class="nav">
      <button onclick="window.location.href='home1.php'">🏠 Home</button>
      <button onclick="window.location.href='tournament.html'">🏆 Tournaments</button>
      <button class="active">👤 Profile</button>
    </nav>
  </aside>

  <!-- MAIN -->
  <main class="main">

    <!-- TOPBAR -->
    <div class="topbar">
      <div class="search"><input placeholder="Search players, teams, tournaments..."></div>
      <div class="action-btns">
        <button class="btn-outline" onclick="window.location.href='post_create.html'">Create Post</button>
        <div style="width:44px;height:44px;border-radius:50%;background:#ffdfcc;display:grid;place-items:center;color:#09090a;font-weight:700">
          <?php echo strtoupper(substr($uname,0,2)); ?>
        </div>
      </div>
    </div>

    <!-- PROFILE HEADER -->
    <section class="profile-card">
      <div class="profile-banner" style="background-image:url('<?php echo $banner; ?>')">

        <div style="display:flex;align-items:center;">
          <img class="avatar" src="<?php echo"$avatar";?>" style="background-size:cover;background-position:center;">

          <div class="profile-meta">
            <div class="info">
              <h2><?php echo htmlspecialchars($uname); ?></h2>
              <p>
                <?php echo htmlspecialchars($name); ?>
                <span style="display:block;color:#cfc9ff;margin-top:6px">
                  <?php echo htmlspecialchars($location); ?>
                </span>
              </p>
            </div>
          </div>
        </div>

        <div class="profile-actions" style="position:absolute;right:18px;top:18px">
          <button class="btn-outline">Connect</button>
          <button class="btn-primary">Message</button>
        </div>
      </div>
    </section>

    <!-- CONTENT -->
    <section class="content">

      <!-- LEFT COLUMN -->
 <!-- RIGHT COLUMN -->
<div>
  <div class="card" style="margin-bottom:18px">

    <div class="profile-extra" style="width: full;">
      <?php if(!empty($user['headline'])) : ?>
        <h3 class="section-title">Headline</h3>
        <p class="section-text"><?php echo htmlspecialchars($user['headline']); ?></p>
      <?php endif; ?>

      <?php if(!empty($user['bio'])) : ?>
        <h3 class="section-title">Bio</h3>
        <p class="section-text"><?php echo nl2br(htmlspecialchars($user['bio'])); ?></p>
      <?php endif; ?>

      <?php if(!empty($user['games'])) : ?>
        <h3 class="section-title">Games</h3>
        <p class="section-text"><?php echo htmlspecialchars($user['games']); ?></p>
      <?php endif; ?>

      <?php if(!empty($user['skills'])) : ?>
        <h3 class="section-title">Skills</h3>
        <p class="section-text"><?php echo nl2br(htmlspecialchars($user['skills'])); ?></p>
      <?php endif; ?>
    </div>

  </div>
</div>

      <!-- RIGHT COLUMN -->
     

    </section>
    

  </main>
</div>

<script>
document.getElementById('gallery').addEventListener('click', (e)=>{
  const thumb = e.target.closest('.thumb');
  if(!thumb) return;
  const src = thumb.querySelector('img').src;
  const modal = document.createElement('div');
  modal.style.cssText="position:fixed;inset:0;display:grid;place-items:center;background:rgba(0,0,0,0.8);z-index:9999";
  modal.innerHTML = `<div style='max-width:90%;max-height:90%;border-radius:12px;overflow:hidden'>
      <img src='${src}' style='width:100%;height:100%;object-fit:contain'>
    </div>`;
  modal.onclick = ()=> modal.remove();
  document.body.appendChild(modal);
});
</script>

</body>
</html>
