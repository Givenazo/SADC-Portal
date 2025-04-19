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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<style>
    /* Info Cards */
    .info-cards-container {
        display: flex;
        gap: 20px;
        margin: 2rem auto;
        padding: 20px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        max-width: 1200px;
        width: 90%;
    }

    .info-card {
        flex: 1;
        background: white;
        padding: 2rem;
        border-radius: 10px;
        transition: all 0.3s ease;
        text-align: left;
        border: none;
        box-shadow: none;
        min-width: 250px;
    }

    .info-card .icon {
        font-size: 2rem;
        margin-bottom: 1rem;
        display: inline-block;
        padding: 0;
        border-radius: 0;
        color: #185a9d;
    }

    .info-card h3 {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #2d3748;
    }

    .info-card p {
        color: #718096;
        line-height: 1.5;
        margin-bottom: 0;
        font-size: 0.95rem;
    }

    @media (max-width: 768px) {
        .info-cards-container {
            flex-direction: column;
        }
        .info-card {
            width: 100%;
        }
    }

    /* Social Links */
    .social-links {
        margin-top: 2rem;
        margin-bottom: 2rem;
    }

    .social-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: white;
        color: #2d3748;
        font-size: 1.5rem;
        margin: 0 0.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .social-link:hover {
        transform: translateY(-3px);
        color: #4299e1;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
</style>

<!-- Info Cards -->
<div class="container-fluid px-4">
    <div class="info-cards-container">
        <div class="info-card">
            <i class="bi bi-building icon"></i>
            <h3>Visit Us</h3>
            <p>123 Sacky Shikufa Avenue<br>Windhoek, Namibia<br>9000</p>
        </div>
        <div class="info-card">
            <i class="bi bi-telephone icon"></i>
            <h3>Call Us</h3>
            <p>Tel: +264 81 143 3825<br>Fax: +264 61 123 4567<br>Mon-Fri 8:00-17:00</p>
        </div>
        <div class="info-card">
            <i class="bi bi-envelope icon"></i>
            <h3>Email Us</h3>
            <p>General: info@sadcnews.com<br>Support: help@sadcnews.com<br>Press: media@sadcnews.com</p>
        </div>
        <div class="info-card">
            <i class="bi bi-chat-dots icon"></i>
            <h3>Live Chat</h3>
            <p>Chat with our support team<br>Response time: 5 mins<br>24/7 Support</p>
        </div>
    </div>

    <!-- Social Links -->
    <div class="social-links text-center">
        <h2 class="mb-4">Connect With Us</h2>
        <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
        <a href="#" class="social-link"><i class="bi bi-twitter"></i></a>
        <a href="#" class="social-link"><i class="bi bi-linkedin"></i></a>
        <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
        <a href="#" class="social-link"><i class="bi bi-youtube"></i></a>
    </div>
</div>
@endsection
