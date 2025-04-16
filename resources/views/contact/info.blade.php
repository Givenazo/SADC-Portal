@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 fw-bold text-primary">Contact Information</h1>
    <div class="mb-5">
        <h2>About Us</h2>
        <p>We are a leading regional portal dedicated to connecting SADC countries through digital media, news, and educational resources. Our mission is to foster collaboration, share knowledge, and empower communities across Southern Africa.</p>
    </div>
    <div class="row mb-5">
        <div class="col-md-6">
            <h3>Contact Details</h3>
            <ul class="list-unstyled">
                <li><strong>Phone:</strong> +27 12 345 6789</li>
                <li><strong>Email:</strong> info@sadcportal.org</li>
                <li><strong>Physical Address:</strong> 123 SADC Avenue, Pretoria, South Africa</li>
            </ul>
            <h4 class="mt-4">Business Hours</h4>
            <ul class="list-unstyled">
                <li>Monday - Friday: 08:00 - 17:00</li>
                <li>Saturday: 09:00 - 13:00</li>
                <li>Sunday & Public Holidays: Closed</li>
            </ul>
        </div>
        <div class="col-md-6">
            <h3>Find Us</h3>
            <div style="height:200px; background:#f1f1f1; display:flex; align-items:center; justify-content:center; border-radius:8px;">
                <span class="text-muted">[Google Map Placeholder]</span>
            </div>
            <h4 class="mt-4">Follow Us</h4>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="#" class="text-primary">Facebook</a></li>
                <li class="list-inline-item"><a href="#" class="text-info">Twitter</a></li>
                <li class="list-inline-item"><a href="#" class="text-danger">YouTube</a></li>
            </ul>
        </div>
    </div>
    <div class="mb-5">
        <h2>Frequently Asked Questions (FAQ)</h2>
        <div class="accordion" id="faqAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="faq1">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                        What is SADC Portal?
                    </button>
                </h2>
                <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="faq1" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        SADC Portal is an online platform for sharing news, videos, and resources among Southern African Development Community countries.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="faq2">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                        How can I contact support?
                    </button>
                </h2>
                <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="faq2" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        You can email us at info@sadcportal.org or call our support line at +27 12 345 6789.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="faq3">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                        Where are you located?
                    </button>
                </h2>
                <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="faq3" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Our main office is located at 123 SADC Avenue, Pretoria, South Africa.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="faq4">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                        How do I upload a video?
                    </button>
                </h2>
                <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="faq4" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Register or log in, then use the 'Upload Video' link in the menu to submit your content.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <h2>Other Information</h2>
        <ul>
            <li>VAT Number: 1234567890</li>
            <li>Registration Number: 2025/123456/07</li>
            <li>Customer Support: support@sadcportal.org</li>
        </ul>
    </div>
</div>
@endsection
