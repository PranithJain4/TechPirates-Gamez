<?php  
require 'connection.php';   // your DB connection file

// Fetch posts from DB
$sql = "SELECT uname, created_at, content, imgpath FROM post ORDER BY post_id DESC";
$result = mysqli_query($conn, $sql);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>LEGENDR Feed</title>
    <style>
  :root{
    --bg-1:#06060a;
    --bg-2:#0b0f17;
    --muted:#bfb7ff;
    --neon-purple:#6a1bff;
    --neon-cyan:#00eaff;
    --glass: rgba(255,255,255,0.04);
    --radius:14px;
}
*{box-sizing:border-box;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,'Helvetica Neue',Arial;}
html,body{height:100%;margin:0;background:
    radial-gradient(1200px 600px at 10% 10%, rgba(58,30,85,0.12), transparent 8%),
    linear-gradient(180deg,var(--bg-1),var(--bg-2)); color:#e9e6ff; -webkit-font-smoothing:antialiased}

/* animated grid */
body::before{
    content:"";position:fixed;inset:0;
    background-image:
      linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
      linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
    background-size:40px 40px; opacity:0.06; pointer-events:none; animation:gridMove 18s linear infinite;
}
@keyframes gridMove{from{transform:translateY(0)}to{transform:translateY(-40px)}}

.app{display:flex;gap:24px;min-height:100vh;padding:28px;}

/* SIDEBAR */
.sidebar{
    width:270px;padding:20px;border-radius:16px;background:linear-gradient(180deg,rgba(255,255,255,0.02),transparent);
    border:1px solid rgba(138,59,255,0.08);backdrop-filter: blur(6px);box-shadow:0 8px 30px rgba(0,0,0,0.6);position:relative;
}
.brand{display:flex;gap:12px;align-items:center;margin-bottom:20px}
.brand img{width:56px;height:56px;border-radius:10px;object-fit:cover;border:1px solid rgba(255,255,255,0.06)}
.brand h1{font-size:18px;margin:0}
.brand p{margin:0;font-size:12px;color:#cfc9ff}

.nav{display:flex;flex-direction:column;gap:10px;margin-top:18px}
.nav .btn{padding:10px;border-radius:10px;background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.03);cursor:pointer;display:flex;align-items:center;gap:10px}
.nav .btn.active{background:linear-gradient(90deg, rgba(138,59,255,0.12), rgba(0,234,255,0.06));box-shadow:0 10px 30px rgba(138,59,255,0.06)}
.small-muted{font-size:12px;color:#bdb6e6;margin-top:12px}

/* MAIN */
.main{flex:1;display:flex;flex-direction:column;gap:18px}
.topbar{display:flex;align-items:center;justify-content:space-between;gap:12px}
.title-group h2{margin:0;font-size:20px}
.title-group p{margin:0;font-size:13px;color:#d6d0ff}
.controls{display:flex;gap:10px;align-items:center}

.search{
    display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:12px;width:420px;
    background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
    border:1px solid rgba(255,255,255,0.03);
}
.search input{background:transparent;border:none;outline:none;color:inherit;font-size:14px;width:100%}
.kbd{font-size:12px;color:var(--muted);padding:6px 8px;border-radius:8px;background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.03)}

/* hero */
.hero{display:flex;justify-content:space-between;align-items:center;padding:14px;border-radius:12px;background:linear-gradient(90deg, rgba(138,59,255,0.04), rgba(0,234,255,0.02));border:1px solid rgba(138,59,255,0.04)}
.hero-left h3{margin:0}
.hero-right{width:160px;height:110px;border-radius:10px;background-image:url('/mnt/data/e1601faf-eedb-47ec-ba94-c4eca25e2e17.png');background-size:cover;background-position:center;border:1px solid rgba(255,255,255,0.03)}

/* FEED */
.feed{margin-top:8px;display:grid;grid-template-columns:1fr;gap:18px}
.card{position:relative;background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));border-radius:14px;padding:18px;border:1px solid rgba(255,255,255,0.03);box-shadow:0 12px 40px rgba(0,0,0,0.5);transition:transform .16s,box-shadow .16s}
.card:hover{transform:translateY(-6px);box-shadow:0 24px 70px rgba(138,59,255,0.08)}
.post-head{display:flex;justify-content:space-between;align-items:center}
.author{display:flex;gap:12px;align-items:center}
.avatar{width:52px;height:52px;border-radius:10px;background:linear-gradient(90deg,var(--neon-purple),var(--neon-cyan));border:2px solid rgba(255,255,255,0.06)}
.author-meta h3{margin:0;font-size:16px}
.author-meta span{font-size:13px;color:#d6d0ff}
.post-content{margin-top:12px;color:#e6e2ff;line-height:1.45}

/* ✔ FIXED IMAGE CODE */
.post-media{
    margin-top:12px;
    width:100%;
    height:360px;
    border-radius:12px;
    overflow:hidden;
    display:flex;
    justify-content:center;
    align-items:center;
}
.post-media img{
    width:100%;
    height:100%;
    object-fit:cover;
    display:block;
}

.post-actions{display:flex;gap:10px;margin-top:12px;align-items:center}
.chip{padding:8px 12px;border-radius:999px;background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.03);cursor:pointer}
.icon{width:40px;height:40px;border-radius:10px;display:grid;place-items:center;cursor:pointer;background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.03)}
.icon:hover{transform:translateY(-3px);box-shadow:0 12px 30px rgba(138,59,255,0.08)}

/* admin action container */
.admin-actions{display:flex;gap:8px}

/* bottom sheet */
.sheet{
    position:fixed;left:0;right:0;bottom:-100%;height:40%;background:linear-gradient(180deg, rgba(11,15,23,0.96), rgba(6,6,10,0.98));
    border-top-left-radius:18px;border-top-right-radius:18px;border:1px solid rgba(255,255,255,0.04);padding:18px;box-shadow:0 -12px 40px rgba(0,0,0,0.6);transition:bottom .26s cubic-bezier(.2,.9,.2,1);z-index:60;
}
.sheet.open{bottom:0}
.sheet .drag{width:64px;height:6px;background:rgba(255,255,255,0.06);margin:0 auto;border-radius:6px;margin-bottom:12px}
.sheet h4{margin:0 0 8px 0}
.results{display:flex;flex-direction:column;gap:10px;overflow:auto;height:calc(100% - 36px);padding-right:6px}
.result-item{display:flex;gap:12px;align-items:center;padding:10px;border-radius:10px;background:rgba(255,255,255,0.01);border:1px solid rgba(255,255,255,0.02);cursor:pointer}
.result-item img{width:46px;height:46px;border-radius:8px;object-fit:cover}

/* responsive */
@media(max-width:920px){
    .sidebar{display:none}
    .search{width:100%}
    .app{padding:14px}
    .post-media{height:220px}
}
.logout-btn {
    position: absolute;
    top: 10px;
    right: 35px;
    padding: 8px 18px;
    font-size: 14px;
    border: none;
    border-radius: 8px;
    cursor: pointer;

    background: linear-gradient(90deg, #ff3b3b, #ff8585);
    color: white;

    box-shadow: 0 0 12px rgba(255, 59, 59, 0.5);
    transition: 0.2s;
}

.logout-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 0 16px rgba(255, 59, 59, 0.7);
}

  </style>
</head>

<body>

<div class="app">
 <button class="logout-btn" onclick="logout()">Logout</button>

    <!-- SIDEBAR + TOPBAR + HERO (KEEP YOUR ORIGINAL CODE) -->
<aside class="sidebar" id="sidebar">
      <div class="brand">
        <img src="logo\logo.jpg" alt="org logo">
        <div>
          <h1 id="brandTitle">GAMEZ</h1>
          <p class="org-role">Home page</p>
          
        </div>
      </div>

      <nav class="nav" aria-label="sidebar">
        <div class="btn active" data-nav="home" >🏠 Home</div>
        <div class="btn" data-nav="members" onclick="window.location.href='post_create.html'">🎬Post</div>
        <div class="btn" data-nav="tournaments" onclick="window.location.href='tournament.html'">🎮 Tournaments</div>
        <div class="btn" data-nav="tournaments" onclick="window.location.href='profile.php'">👤 Profile</div>
        
      </nav>
    </aside>


     

    <main class="main">

        <!-- FEED -->
        <section class="feed" id="feed">
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <article class="card">

                    <div class="post-head">
                        <div class="author">
                            <div class="avatar"></div>
                            <div class="author-meta">
                                <h3><?= htmlspecialchars($row['uname']) ?></h3>
                                <span><?= htmlspecialchars($row['created_at']) ?></span>
                            </div>
                        </div>

                        <!-- Admin Options -->
                        <div class="admin-actions">
                            <div class="icon" data-action="view" onclick="view()">👁️</div>
                            <div class="icon" data-action="edit">✏️</div>
                            <div class="icon" data-action="delete">🗑️</div>
                        </div>
                    </div>

                    <div class="post-content">
                        <?= nl2br(htmlspecialchars($row['content'])) ?>
                    </div>

                   
                    <?php if(!empty($row['imgpath'])): ?>
                    <div class="post-media">
                        <img src="<?= $row['imgpath'] ?>" alt="post image">
                    </div>
                    <?php endif; ?>

                    <div class="post-actions">
                        <div class="chip">View Comments</div>
                        <div style="flex:1"></div>
                        <div class="chip">Share</div>
                    </div>

                </article>
            <?php endwhile; ?>
        </section>

    </main>
</div>

<script>
    
    function logout()
    {
        document.cookie = "cookieName=name; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;"
    window.location.href="game.html";
    }
</script>

</body>
</html>
