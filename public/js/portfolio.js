/**
 * HASNAIN AL IFAN — PORTFOLIO CLIENT LOGIC
 * Lightweight, accessible, and fast.
 */

document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  // 1. Mobile Menu Drawer Toggle
  const menuToggle = document.getElementById('menu-toggle');
  const mobileDrawer = document.getElementById('mobile-drawer');

  if (menuToggle && mobileDrawer) {
    menuToggle.addEventListener('click', function () {
      const isOpen = mobileDrawer.classList.toggle('open');
      menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      if (isOpen) {
        document.body.style.overflow = 'hidden';
      } else {
        document.body.style.overflow = '';
      }
    });

    // Close mobile drawer when clicking any nav link
    const mobileLinks = mobileDrawer.querySelectorAll('a');
    mobileLinks.forEach(function (link) {
      link.addEventListener('click', function () {
        mobileDrawer.classList.remove('open');
        menuToggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
      });
    });

    // Close on Escape key
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && mobileDrawer.classList.contains('open')) {
        mobileDrawer.classList.remove('open');
        menuToggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
      }
    });
  }

  // 2. Active Section Scroll Spy
  const sections = document.querySelectorAll('section[id]');
  const navLinks = document.querySelectorAll('.nav-link, .mobile-nav-link');

  function updateActiveNav() {
    const scrollY = window.pageYOffset || document.documentElement.scrollTop;
    const headerHeight = 90;

    sections.forEach(function (section) {
      const sectionHeight = section.offsetHeight;
      const sectionTop = section.offsetTop - headerHeight;
      const sectionId = section.getAttribute('id');

      if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
        navLinks.forEach(function (link) {
          if (link.getAttribute('href') === '#' + sectionId) {
            link.classList.add('active');
          } else if (link.getAttribute('href').startsWith('#')) {
            link.classList.remove('active');
          }
        });
      }
    });
  }

  window.addEventListener('scroll', updateActiveNav, { passive: true });
  updateActiveNav();

  // 3. Project Filter Tabs
  const filterButtons = document.querySelectorAll('.filter-btn');
  const projectCards = document.querySelectorAll('[data-project-category]');

  if (filterButtons.length > 0 && projectCards.length > 0) {
    filterButtons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        const filter = this.getAttribute('data-filter');

        filterButtons.forEach(function (b) {
          b.classList.remove('active');
          b.setAttribute('aria-pressed', 'false');
        });
        this.classList.add('active');
        this.setAttribute('aria-pressed', 'true');

        projectCards.forEach(function (card) {
          const category = card.getAttribute('data-project-category');
          if (filter === 'all' || category === filter) {
            card.style.display = '';
          } else {
            card.style.display = 'none';
          }
        });
      });
    });
  }

  // 4. DoctorQR Workflow Showcase Tabs
  const workflowTabs = document.querySelectorAll('.workflow-tab-btn');
  const workflowPanes = document.querySelectorAll('.workflow-pane');

  if (workflowTabs.length > 0 && workflowPanes.length > 0) {
    workflowTabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        const targetId = this.getAttribute('data-workflow-target');

        workflowTabs.forEach(function (t) {
          t.classList.remove('active');
          t.setAttribute('aria-selected', 'false');
        });
        workflowPanes.forEach(function (p) {
          p.classList.remove('active');
        });

        this.classList.add('active');
        this.setAttribute('aria-selected', 'true');

        const activePane = document.getElementById(targetId);
        if (activePane) {
          activePane.classList.add('active');
        }
      });
    });
  }

  // 5. Contact Form Submission Handling
  const contactForm = document.getElementById('contact-form');
  const contactFeedback = document.getElementById('contact-feedback');

  if (contactForm && contactFeedback) {
    contactForm.addEventListener('submit', function (e) {
      e.preventDefault();

      const submitBtn = contactForm.querySelector('button[type="submit"]');
      const originalBtnText = submitBtn ? submitBtn.innerHTML : 'Send Message';

      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Sending message...';
      }

      const formData = new FormData(contactForm);

      fetch(contactForm.action, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
        },
        body: formData,
      })
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          if (data.status === 'success') {
            contactFeedback.className = 'form-feedback success';
            contactFeedback.innerHTML = `
              <strong>Success!</strong> ${data.message}
              <div style="margin-top: 8px;">
                <a href="${data.mailto_url}" style="color: #3B82F6; text-decoration: underline; font-weight: 600;">
                  Click here to also open in your email client &rarr;
                </a>
              </div>
            `;
            contactFeedback.style.display = 'block';
            contactForm.reset();
          } else {
            throw new Error(data.message || 'Submission error');
          }
        })
        .catch(function () {
          // Graceful fallback to client mailto
          const name = encodeURIComponent(formData.get('name') || '');
          const subject = encodeURIComponent(formData.get('subject') || 'Portfolio Inquiry');
          const message = encodeURIComponent(formData.get('message') || '');
          const fallbackMailto = `mailto:hasnainalifan23@gmail.com?subject=${subject}&body=From:%20${name}%0A%0A${message}`;

          contactFeedback.className = 'form-feedback success';
          contactFeedback.innerHTML = `
            Thank you! You can directly send your message via:
            <a href="${fallbackMailto}" style="color: #3B82F6; text-decoration: underline; font-weight: 600; margin-left: 4px;">
              hasnainalifan23@gmail.com
            </a>
          `;
          contactFeedback.style.display = 'block';
        })
        .finally(function () {
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
          }
        });
    });
  }

  // 6. Profile Image Error Fallback
  const profileImg = document.getElementById('hero-profile-image');
  const fallbackAvatar = document.getElementById('hero-fallback-avatar');

  if (profileImg && fallbackAvatar) {
    profileImg.addEventListener('error', function () {
      profileImg.style.display = 'none';
      fallbackAvatar.style.display = 'flex';
    });
  }
});
