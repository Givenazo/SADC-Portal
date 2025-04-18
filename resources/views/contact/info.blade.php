@extends('layouts.app')

@section('header')
<div class="text-center mb-6">
  <div class="bg-light py-3 px-2 mb-2 text-center" style="border-radius:0.5rem;">
    <h1 class="fw-bold d-flex flex-column align-items-center justify-content-center sadc-header-darkblue" style="font-size:3rem;margin-bottom:0;font-weight:bold;text-align:center;">
      <span><i class="bi bi-person-lines-fill me-2 sadc-header-darkblue" style="font-size:2.5rem;"></i></span>
      Support and Info
    </h1>
    <span class="text-gray-600 text-lg" style="font-size:1.15rem;display:block;margin-top:0;text-align:center;margin-left:1cm;">Get support and information for the SADC News Portal.</span>
  </div>
</div>
@endsection



@section('content')

<style>
  .info-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 12px 0 rgba(44,62,80,0.08);
    transition: box-shadow 0.2s;
    padding: 22px 8px 18px 8px;
    min-height: 220px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin-bottom: 0;
  }
  .info-card .icon {
    font-size: 2.1rem;
    margin-bottom: 4px;
  }
  .info-card h6 {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #222;
  }
  .info-card p {
    color: #888;
    font-size: 1rem;
    margin-bottom: 0;
  }
  .info-card .about { color: #1677fa; }
  .info-card .values { color: #14b86b; }
  .info-card .mission { color: #ff4747; }
  .info-card .contact { color: #16b1fa; }
  @media (max-width: 991px) {
    .info-card { min-height: 220px; padding: 24px 8px; }
  }
</style>
<div class="container py-4">
  <div class="row g-2 mb-2">
    <div class="col-lg-3 col-md-6">
      <div class="info-card">
        <i class="bi bi-info-circle-fill icon about"></i>
        <h6>About Us</h6>
        <p>Learn about the SADC News Portal, our history, and our mission to deliver reliable news across the region.</p>
      </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="info-card">
        <i class="bi bi-gem icon values"></i>
        <h6>Core Values</h6>
        <p>Integrity, transparency, and a commitment to truthful reporting are at the heart of everything we do.</p>
      </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="info-card">
        <i class="bi bi-bullseye icon mission"></i>
        <h6>Mission</h6>
        <p>To empower communities by providing timely, accurate, and relevant news for all SADC member states.</p>
      </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="info-card">
        <i class="bi bi-envelope-fill icon contact"></i>
        <h6>Contact Info</h6>
        <p class="text-center" style="width:100%">Email: info@sadcnews.com<br>Phone: +264 81 143 3825<br>Address: 123 Sacky Shikufa Avenue, Windhoek, Namibia</p>
      </div>
    </div>
  </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="/css/sadc-custom.css">
<noscript><style>.bi{display:none !important;}</style><span style='color:red'>Icons require JavaScript and CDN access.</span></noscript>

@endsection
