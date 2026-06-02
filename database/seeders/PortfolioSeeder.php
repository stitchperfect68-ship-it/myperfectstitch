<?php

namespace Database\Seeders;

use App\Models\PortfolioImage;
use App\Models\PortfolioProject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        PortfolioImage::truncate();
        PortfolioProject::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $projects = [
            // ── BAGS ──────────────────────────────────────────────────────────────
            [
                'title'        => 'Embroidered Laptop Bags for <em>Anakazi Banking</em>',
                'client_name'  => 'Stanbic Bank',
                'client_badge' => 'Stanbic Bank · Anakazi Banking',
                'category'     => 'bags',
                'description'  => "Embroidered laptop bags for Anakazi Banking.\n\nEmbroidery remains one of the strongest branding methods for logos that are bold, simple, and built to stand out.\n\nThe result? A professional finish that carries your identity everywhere your team goes.",
                'cta_text'     => 'Get a Quote',
                'layout_type'  => 'layout-wide',
                'gallery_type' => 'g-2',
                'images'       => [
                    ['path' => 'assets/stanbic1.jpg', 'role' => 'hero'],
                ],
            ],
            [
                'title'        => 'Lusaka Securities <em>Exchange (LuSE)</em>',
                'client_name'  => 'LuSE',
                'client_badge' => 'Financial Services · Corporate Branding',
                'category'     => 'bags',
                'description'  => "The Lusaka Securities Exchange has elevated their corporate identity with custom branded bags from My Perfect Stitch — purpose-built for a professional team at the heart of Zambia's capital markets.\n\nGet in touch today to learn how My Perfect Stitch can help your organisation tell its story through high-quality, branded bags.",
                'cta_text'     => 'Start Your Order',
                'layout_type'  => 'layout-wide',
                'gallery_type' => 'g-main-plus',
                'images'       => [
                    ['path' => 'assets/mof1.jpg', 'role' => 'hero'],
                    ['path' => 'assets/mof2.jpg', 'role' => 'secondary'],
                ],
            ],
            [
                'title'        => 'Branded Laptop Sleeves for <em>Hobbiton</em>',
                'client_name'  => 'Hobbiton',
                'client_badge' => 'Tech Company · Team Identity',
                'category'     => 'bags',
                'description'  => "Hobbiton's software development team is now equipped with branded laptop sleeves designed to reflect both their tech culture and brand identity.\n\nAs companies increasingly recognize the importance of personalized, functional accessories, we're here to provide solutions that enhance both team identity and professionalism.\n\nAs you plan for the year ahead, consider how your team carries your brand every day. Let's work together to design customized, high-quality bags that align with your 2026 goals.",
                'cta_text'     => 'Plan for 2026',
                'layout_type'  => 'layout-wide',
                'gallery_type' => 'g-2',
                'images'       => [
                    ['path' => 'assets/hob.jpg',  'role' => 'hero'],
                    ['path' => 'assets/hob1.jpg', 'role' => 'secondary'],
                ],
            ],
            [
                'title'        => 'Laptop Sleeves for <em>Airtel</em>',
                'client_name'  => 'Airtel',
                'client_badge' => 'Telecoms · Dual-Option Branding',
                'category'     => 'bags',
                'description'  => "For Airtel, we delivered two thoughtfully designed options — each serving a different work style.\n\nThe new-design laptop sleeve with handles — for professionals who need structure, durability, and easy carry on the move.\n\nThe standard laptop sleeve — for those who prefer a lighter, sleek, and minimal everyday option.\n\nSame identity. Different needs. One brand experience.\n\nWe are currently taking orders for both options, customised to your organisation's branding and requirements.",
                'cta_text'     => 'Order Now',
                'layout_type'  => 'layout-wide',
                'gallery_type' => 'g-2x2',
                'images'       => [
                    ['path' => 'assets/airtel.jpg',  'role' => 'hero'],
                    ['path' => 'assets/airtel1.jpg', 'role' => 'secondary'],
                    ['path' => 'assets/airtel2.jpg', 'role' => 'secondary'],
                    ['path' => 'assets/airtel3.jpg', 'role' => 'secondary'],
                ],
            ],
            // ── INTERIOR ──────────────────────────────────────────────────────────
            [
                'title'        => 'Interior Design for <em>Bongohive</em>',
                'client_name'  => 'Bongohive',
                'client_badge' => 'Innovation Hub · Interior Design',
                'category'     => 'interior',
                'description'  => "Bongohive is one of Lusaka's most vibrant coworking and innovation hubs. We partnered with their team to design and execute an interior that reflects their collaborative, creative, and entrepreneurial culture.\n\nThe space needed to feel open, inspiring, and professional — an environment where ideas grow. Every detail, from upholstery to soft furnishings, was carefully considered to bring that vision to life.",
                'cta_text'     => 'Get a Quote',
                'layout_type'  => 'layout-wide',
                'gallery_type' => 'g-2x2',
                'images'       => [
                    ['path' => 'assets/portfolio/bongohive/b1 (1).jpg', 'role' => 'hero'],
                    ['path' => 'assets/portfolio/bongohive/b1 (2).jpg', 'role' => 'secondary'],
                    ['path' => 'assets/portfolio/bongohive/b1 (3).jpg', 'role' => 'secondary'],
                    ['path' => 'assets/portfolio/bongohive/b1 (4).jpg', 'role' => 'secondary'],
                ],
            ],
            [
                'title'        => 'Interior Design for <em>Hybrid</em>',
                'client_name'  => 'Hybrid',
                'client_badge' => 'Corporate · Interior Design',
                'category'     => 'interior',
                'description'  => "For Hybrid, we delivered a tailored interior design project that blends functionality with a strong brand presence.\n\nThe brief called for a workspace that feels modern and purposeful — one that communicates the company's identity to both team members and visiting clients. We matched every material and finish to their brand palette for a cohesive result.",
                'cta_text'     => 'Start Your Project',
                'layout_type'  => 'layout-wide',
                'gallery_type' => 'g-2x2',
                'images'       => [
                    ['path' => 'assets/portfolio/hybrid/h1 (1).jpg', 'role' => 'hero'],
                    ['path' => 'assets/portfolio/hybrid/h1 (3).jpg', 'role' => 'secondary'],
                    ['path' => 'assets/portfolio/hybrid/h1 (4).jpg', 'role' => 'secondary'],
                    ['path' => 'assets/portfolio/hybrid/h1 (5).jpg', 'role' => 'secondary'],
                ],
            ],
            [
                'title'        => "Mosi-oa-Tunya Innovation Hub for <em>ABSA</em>",
                'client_name'  => 'ABSA Bank',
                'client_badge' => 'Financial Services · Interior Design',
                'category'     => 'interior',
                'description'  => "ABSA's Mosi-oa-Tunya Innovation space is a flagship initiative designed to foster creativity and collaboration within the financial sector.\n\nWe were privileged to contribute to the interior, creating an environment that bridges the professionalism of a leading African bank with the open, innovative spirit the space needed to embody.",
                'cta_text'     => 'Work With Us',
                'layout_type'  => 'layout-wide',
                'gallery_type' => 'g-main-plus',
                'images'       => [
                    ['path' => 'assets/portfolio/mosi-oa-tunya-innovation ABSA/mosi1 (1).jpg', 'role' => 'hero'],
                    ['path' => 'assets/portfolio/mosi-oa-tunya-innovation ABSA/mosi1 (2).jpg', 'role' => 'secondary'],
                    ['path' => 'assets/portfolio/mosi-oa-tunya-innovation ABSA/mosi1 (3).jpg', 'role' => 'secondary'],
                    ['path' => 'assets/portfolio/mosi-oa-tunya-innovation ABSA/mos (1).jpg',   'role' => 'secondary'],
                    ['path' => 'assets/portfolio/mosi-oa-tunya-innovation ABSA/mos (2).jpg',   'role' => 'secondary'],
                    ['path' => 'assets/portfolio/mosi-oa-tunya-innovation ABSA/mos (3).jpg',   'role' => 'secondary'],
                    ['path' => 'assets/portfolio/mosi-oa-tunya-innovation ABSA/mos (4).jpg',   'role' => 'secondary'],
                    ['path' => 'assets/portfolio/mosi-oa-tunya-innovation ABSA/mos (5).jpg',   'role' => 'secondary'],
                    ['path' => 'assets/portfolio/mosi-oa-tunya-innovation ABSA/mos (6).jpg',   'role' => 'secondary'],
                    ['path' => 'assets/portfolio/mosi-oa-tunya-innovation ABSA/mos (7).jpg',   'role' => 'secondary'],
                    ['path' => 'assets/portfolio/mosi-oa-tunya-innovation ABSA/mos (8).jpg',   'role' => 'secondary'],
                    ['path' => 'assets/portfolio/mosi-oa-tunya-innovation ABSA/mos (9).jpg',   'role' => 'secondary'],
                ],
            ],
            // ── FURNITURE ─────────────────────────────────────────────────────────
            [
                'title'        => 'Custom Furniture for <em>ABSA</em>',
                'client_name'  => 'ABSA Bank',
                'client_badge' => 'Financial Services · Custom Furniture',
                'category'     => 'furniture',
                'description'  => "For ABSA, we designed and delivered bespoke furniture that meets the high standards of a leading African financial institution — combining durability, aesthetics, and brand alignment.\n\nEvery piece was crafted to complement the professional environment while reflecting ABSA's identity through material choice and finish.",
                'cta_text'     => 'Get a Quote',
                'layout_type'  => 'layout-wide',
                'gallery_type' => 'g-main-plus',
                'images'       => [
                    ['path' => 'assets/portfolio/furniture/absa (1).jpg', 'role' => 'hero'],
                    ['path' => 'assets/portfolio/furniture/absa (2).jpg', 'role' => 'secondary'],
                    ['path' => 'assets/portfolio/furniture/absa (3).jpg', 'role' => 'secondary'],
                    ['path' => 'assets/portfolio/furniture/absa (4).jpg', 'role' => 'secondary'],
                    ['path' => 'assets/portfolio/furniture/absa (5).jpg', 'role' => 'secondary'],
                ],
            ],
            [
                'title'        => 'Custom Furniture for <em>Latitude15</em>',
                'client_name'  => 'Latitude15',
                'client_badge' => 'Tech & Innovation · Custom Furniture',
                'category'     => 'furniture',
                'description'  => "Latitude15 needed furniture that matched the premium and modern feel of their workspace. We delivered custom pieces built to their exact specifications — functional, refined, and built to last.\n\nThe result is a work environment that reflects the quality and professionalism Latitude15 is known for.",
                'cta_text'     => 'Start Your Project',
                'layout_type'  => 'layout-wide',
                'gallery_type' => 'g-2',
                'images'       => [
                    ['path' => 'assets/portfolio/furniture/latitude15 (1).jpg', 'role' => 'hero'],
                    ['path' => 'assets/portfolio/furniture/latitude15 (2).jpg', 'role' => 'secondary'],
                ],
            ],
            [
                'title'        => 'Custom Furniture for <em>Bongohive</em>',
                'client_name'  => 'Bongohive',
                'client_badge' => 'Innovation Hub · Custom Furniture',
                'category'     => 'furniture',
                'description'  => "Bongohive's collaborative spirit demanded furniture that is as dynamic as the people using it. We crafted custom pieces that support both focused work and open collaboration — built to move with the space.\n\nDurable, thoughtfully designed, and perfectly aligned with the hub's creative identity.",
                'cta_text'     => 'Get a Quote',
                'layout_type'  => 'layout-wide',
                'gallery_type' => 'g-main-plus',
                'images'       => [
                    ['path' => 'assets/portfolio/furniture/bongohive (1).jpg', 'role' => 'hero'],
                    ['path' => 'assets/portfolio/furniture/bongohive (2).jpg', 'role' => 'secondary'],
                    ['path' => 'assets/portfolio/furniture/bongohive (3).jpg', 'role' => 'secondary'],
                ],
            ],
            [
                'title'        => 'Custom Furniture for <em>Hybrid</em>',
                'client_name'  => 'Hybrid',
                'client_badge' => 'Corporate · Custom Furniture',
                'category'     => 'furniture',
                'description'  => "For Hybrid, we produced custom furniture that elevates the office environment — designed to reflect the company's modern, professional brand while providing comfort and longevity.\n\nEach piece was tailored to fit the specific dimensions and aesthetic of their workspace.",
                'cta_text'     => 'Start Your Project',
                'layout_type'  => 'layout-wide',
                'gallery_type' => 'g-2',
                'images'       => [
                    ['path' => 'assets/portfolio/furniture/hybrid (1).jpg', 'role' => 'hero'],
                    ['path' => 'assets/portfolio/furniture/hybrid (2).jpg', 'role' => 'secondary'],
                ],
            ],
        ];

        foreach ($projects as $i => $data) {
            $images = $data['images'];
            unset($data['images']);

            $project = PortfolioProject::create(array_merge($data, [
                'slug'       => Str::slug(strip_tags($data['title'])) . '-' . Str::random(4),
                'is_active'  => true,
                'sort_order' => $i,
            ]));

            foreach ($images as $j => $img) {
                PortfolioImage::create([
                    'portfolio_project_id' => $project->id,
                    'path'       => $img['path'],
                    'alt'        => $project->client_name,
                    'role'       => $img['role'],
                    'sort_order' => $j,
                ]);
            }
        }
    }
}
