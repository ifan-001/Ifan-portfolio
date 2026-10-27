/**
 * Hasnain Al Ifan - Personal Portfolio
 * In-Place Visual Content Management System (Admin CMS Layer)
 * Brand Colors: #0B1120 (Navy), #F5F7FA (Off-White), #3B82F6 (Electric Blue)
 */

document.addEventListener('DOMContentLoaded', function () {
    // Escape key closes modals
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeAllAdminModals();
        }
    });
});

// Toast notification helper
function showAdminToast(message, isError = false) {
    const toast = document.getElementById('adminToast');
    const msg = document.getElementById('adminToastMsg');
    if (!toast || !msg) return;

    msg.textContent = message;
    toast.style.borderColor = isError ? '#EF4444' : '#3B82F6';
    toast.querySelector('.admin-toast-icon').textContent = isError ? '✕' : '✓';
    toast.querySelector('.admin-toast-icon').style.color = isError ? '#EF4444' : '#10B981';

    toast.classList.add('show');
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3800);
}

// Modal open/close helpers
function openAdminModal(modalId) {
    const backdrop = document.getElementById('adminModalBackdrop');
    const modal = document.getElementById(modalId);
    if (!modal || !backdrop) return;

    backdrop.classList.add('show');
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeAdminModal(modalId) {
    const modal = document.getElementById(modalId);
    const backdrop = document.getElementById('adminModalBackdrop');
    if (modal) modal.classList.remove('show');
    if (backdrop) backdrop.classList.remove('show');
    document.body.style.overflow = '';
}

function closeAllAdminModals() {
    document.querySelectorAll('.admin-modal.show').forEach(m => m.classList.remove('show'));
    const backdrop = document.getElementById('adminModalBackdrop');
    if (backdrop) backdrop.classList.remove('show');
    document.body.style.overflow = '';
}

// Toggle visitor view (clean preview without edit badges)
function toggleVisitorPreview() {
    document.body.classList.toggle('hide-admin-controls');
    const btnText = document.getElementById('previewBtnText');
    if (document.body.classList.contains('hide-admin-controls')) {
        btnText.textContent = 'Back to Edit';
        showAdminToast('Visitor Preview Mode: Admin edit buttons hidden.');
    } else {
        btnText.textContent = 'Visitor View';
        showAdminToast('Visual Editor Mode: Edit buttons active.');
    }
}

// --------------------------------------------------------------------------
// 1. Profile Photo Upload & Live Fitting Preview
// --------------------------------------------------------------------------
function previewSelectedPhoto(input) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];

    const reader = new FileReader();
    reader.onload = function (e) {
        const previewPortrait = document.getElementById('livePreviewPortrait');
        const previewCircle = document.getElementById('livePreviewCircle');
        if (previewPortrait) previewPortrait.src = e.target.result;
        if (previewCircle) previewCircle.src = e.target.result;
    };
    reader.readAsDataURL(file);
}

function adjustPreviewPosition(posValue) {
    const previewPortrait = document.getElementById('livePreviewPortrait');
    const previewCircle = document.getElementById('livePreviewCircle');
    if (previewPortrait) previewPortrait.style.objectPosition = posValue;
    if (previewCircle) previewCircle.style.objectPosition = posValue;
}

async function handlePhotoUpload(e) {
    e.preventDefault();
    const form = e.target;
    const submitBtns = form.querySelectorAll('button[type="submit"]');
    const headerBtn = document.querySelector('button[form="profilePhotoForm"]');
    const formData = new FormData(form);

    submitBtns.forEach(b => { b.disabled = true; b.textContent = 'Saving...'; });
    if (headerBtn) { headerBtn.disabled = true; headerBtn.textContent = 'Saving...'; }

    try {
        const response = await fetch('/admin/profile/photo', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });

        const data = await response.json();
        if (response.ok && data.status === 'success') {
            showAdminToast(data.message || 'Profile picture updated & fitted successfully!');
            // Update photo immediately on page elements
            const heroImg = document.getElementById('hero-profile-image');
            if (heroImg && data.image_url) {
                heroImg.src = data.image_url;
            }
            document.querySelectorAll('.brand-avatar-img').forEach(img => {
                if (data.image_url) img.src = data.image_url;
            });
            setTimeout(() => {
                closeAdminModal('profilePhotoModal');
            }, 500);
        } else {
            showAdminToast(data.message || 'Photo upload failed. Check file type and size.', true);
        }
    } catch (err) {
        showAdminToast('Network error during photo upload.', true);
    } finally {
        submitBtns.forEach(b => { b.disabled = false; b.textContent = '💾 Save & Apply Photo'; });
        if (headerBtn) { headerBtn.disabled = false; headerBtn.textContent = '💾 Save Photo'; }
    }
}

// --------------------------------------------------------------------------
// 2. Profile Details Update
// --------------------------------------------------------------------------
async function handleProfileUpdate(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    try {
        const response = await fetch('/admin/profile', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });

        const data = await response.json();
        if (response.ok) {
            showAdminToast('Profile details updated successfully!');
            setTimeout(() => {
                window.location.reload();
            }, 700);
        } else {
            showAdminToast(data.message || 'Validation error while saving profile.', true);
        }
    } catch (err) {
        showAdminToast('Failed to save profile changes.', true);
    }
}

// --------------------------------------------------------------------------
// 3. About Me Bio Paragraphs
// --------------------------------------------------------------------------
function addBioParagraph() {
    const container = document.getElementById('bioParagraphsContainer');
    if (!container) return;
    const count = container.querySelectorAll('.bio-para-card').length + 1;

    const card = document.createElement('div');
    card.className = 'bio-para-card';
    card.innerHTML = `
        <div class="bio-para-header">
            <span class="bio-para-num">Paragraph ${count}</span>
            <button type="button" class="bio-para-del-btn" onclick="removeBioParagraph(this)" title="Delete paragraph">&times; Delete</button>
        </div>
        <textarea name="paragraphs[]" class="admin-textarea" rows="3" required placeholder="Write new paragraph content..."></textarea>
    `;
    container.appendChild(card);
    card.querySelector('textarea').focus();
}

function removeBioParagraph(btn) {
    const card = btn.closest('.bio-para-card');
    if (!card) return;
    const container = document.getElementById('bioParagraphsContainer');
    if (container.querySelectorAll('.bio-para-card').length <= 1) {
        showAdminToast('At least one bio paragraph is required.', true);
        return;
    }
    card.remove();
    // Renumber remaining paragraphs
    container.querySelectorAll('.bio-para-card').forEach((c, idx) => {
        c.querySelector('.bio-para-num').textContent = `Paragraph ${idx + 1}`;
    });
}

async function restoreAuthenticBio() {
    if (!confirm('Are you sure you want to restore the authentic 4 paragraphs for Hasnain Al Ifan? Any unsaved edits will be replaced.')) {
        return;
    }

    try {
        const response = await fetch('/admin/profile/about-bio/restore', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });

        const data = await response.json();
        if (response.ok && data.status === 'success') {
            showAdminToast('Authentic 4 paragraphs restored!');
            setTimeout(() => {
                window.location.reload();
            }, 700);
        } else {
            showAdminToast('Failed to restore authentic bio.', true);
        }
    } catch (err) {
        showAdminToast('Network error restoring authentic bio.', true);
    }
}

async function handleAboutBioSave(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    try {
        const response = await fetch('/admin/profile/about-bio', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });

        const data = await response.json();
        if (response.ok && data.status === 'success') {
            showAdminToast('About bio paragraphs updated successfully!');
            setTimeout(() => {
                window.location.reload();
            }, 700);
        } else {
            showAdminToast(data.message || 'Failed to save bio paragraphs.', true);
        }
    } catch (err) {
        showAdminToast('Network error updating bio.', true);
    }
}

// --------------------------------------------------------------------------
// 4. Skills In-Place Add & Edit
// --------------------------------------------------------------------------
function openAddSkillModal() {
    document.getElementById('skillId').value = '';
    document.getElementById('skillMethod').value = 'POST';
    document.getElementById('skillName').value = '';
    document.getElementById('skillProficiency').value = 85;
    document.getElementById('profVal').textContent = '85';
    document.getElementById('skillFeatured').checked = true;
    document.getElementById('skillDeleteBtn').style.display = 'none';
    document.getElementById('skillModalTitle').textContent = 'Add New Skill';
    openAdminModal('skillModal');
}

function openEditSkillModal(id, name, category, proficiency) {
    document.getElementById('skillId').value = id;
    document.getElementById('skillMethod').value = 'PUT';
    document.getElementById('skillName').value = name;
    document.getElementById('skillCategory').value = category;
    document.getElementById('skillProficiency').value = proficiency || 85;
    document.getElementById('profVal').textContent = proficiency || 85;
    document.getElementById('skillFeatured').checked = true;
    document.getElementById('skillDeleteBtn').style.display = 'inline-block';
    document.getElementById('skillModalTitle').textContent = 'Edit Skill: ' + name;
    openAdminModal('skillModal');
}

async function handleSkillSave(e) {
    e.preventDefault();
    const form = e.target;
    const id = document.getElementById('skillId').value;
    const isEdit = !!id;
    const url = isEdit ? `/admin/skills/${id}` : '/admin/skills';
    const formData = new FormData(form);

    try {
        const response = await fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });

        const data = await response.json();
        if (response.ok) {
            showAdminToast(data.message || 'Skill saved successfully!');
            setTimeout(() => window.location.reload(), 600);
        } else {
            showAdminToast(data.message || 'Validation error saving skill.', true);
        }
    } catch (err) {
        showAdminToast('Network error saving skill.', true);
    }
}

async function handleSkillDelete() {
    const id = document.getElementById('skillId').value;
    if (!id || !confirm('Are you sure you want to delete this skill?')) return;

    try {
        const response = await fetch(`/admin/skills/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });

        const data = await response.json();
        if (response.ok) {
            showAdminToast('Skill deleted successfully.');
            setTimeout(() => window.location.reload(), 600);
        } else {
            showAdminToast('Failed to delete skill.', true);
        }
    } catch (err) {
        showAdminToast('Error deleting skill.', true);
    }
}

// --------------------------------------------------------------------------
// 5. Projects In-Place Add & Edit
// --------------------------------------------------------------------------
function openAddProjectModal() {
    document.getElementById('projectId').value = '';
    document.getElementById('projectMethod').value = 'POST';
    document.getElementById('projectTitle').value = '';
    document.getElementById('projectSlug').value = '';
    document.getElementById('projectCategory').value = 'web';
    document.getElementById('projectTagline').value = '';
    document.getElementById('projectSummary').value = '';
    document.getElementById('projectDescription').value = '';
    document.getElementById('projectTech').value = '';
    document.getElementById('projectLiveUrl').value = '';
    document.getElementById('projectGithubUrl').value = '';
    document.getElementById('projectFeatured').checked = false;
    document.getElementById('projectDeleteBtn').style.display = 'none';
    document.getElementById('projectModalTitle').textContent = 'Add New Project';
    openAdminModal('projectModal');
}

function openEditProjectModal(id, title, slug, category, tagline, summary, desc, tech, liveUrl, githubUrl, isFeatured) {
    document.getElementById('projectId').value = id;
    document.getElementById('projectMethod').value = 'PUT';
    document.getElementById('projectTitle').value = title;
    document.getElementById('projectSlug').value = slug;
    document.getElementById('projectCategory').value = category || 'web';
    document.getElementById('projectTagline').value = tagline || '';
    document.getElementById('projectSummary').value = summary || '';
    document.getElementById('projectDescription').value = desc || '';
    document.getElementById('projectTech').value = tech || '';
    document.getElementById('projectLiveUrl').value = liveUrl || '';
    document.getElementById('projectGithubUrl').value = githubUrl || '';
    document.getElementById('projectFeatured').checked = !!isFeatured;
    document.getElementById('projectDeleteBtn').style.display = 'inline-block';
    document.getElementById('projectModalTitle').textContent = 'Edit Project: ' + title;
    openAdminModal('projectModal');
}

async function handleProjectSave(e) {
    e.preventDefault();
    const form = e.target;
    const id = document.getElementById('projectId').value;
    const isEdit = !!id;
    const url = isEdit ? `/admin/projects/${id}` : '/admin/projects';
    const formData = new FormData(form);

    try {
        const response = await fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });

        const data = await response.json();
        if (response.ok) {
            showAdminToast(data.message || 'Project saved successfully!');
            setTimeout(() => window.location.reload(), 600);
        } else {
            showAdminToast(data.message || 'Failed to save project.', true);
        }
    } catch (err) {
        showAdminToast('Network error saving project.', true);
    }
}

async function handleProjectDelete() {
    const id = document.getElementById('projectId').value;
    if (!id || !confirm('Are you sure you want to delete this project?')) return;

    try {
        const response = await fetch(`/admin/projects/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });

        const data = await response.json();
        if (response.ok) {
            showAdminToast('Project deleted successfully.');
            setTimeout(() => window.location.reload(), 600);
        } else {
            showAdminToast('Failed to delete project.', true);
        }
    } catch (err) {
        showAdminToast('Error deleting project.', true);
    }
}

// --------------------------------------------------------------------------
// 6. Experience In-Place Add & Edit
// --------------------------------------------------------------------------
function openAddExperienceModal() {
    document.getElementById('expId').value = '';
    document.getElementById('expMethod').value = 'POST';
    document.getElementById('expCompany').value = '';
    document.getElementById('expLocation').value = 'Dhaka, Bangladesh';
    document.getElementById('expDesignation').value = '';
    document.getElementById('expPeriod').value = '';
    document.getElementById('expType').value = 'Full-time';
    document.getElementById('expTimelineLabel').value = '';
    document.getElementById('expSummary').value = '';
    document.getElementById('expHighlights').value = '';
    document.getElementById('expIsCurrent').checked = true;
    document.getElementById('expDeleteBtn').style.display = 'none';
    document.getElementById('expModalTitle').textContent = 'Add Work Experience';
    openAdminModal('experienceModal');
}

function openEditExperienceModal(id, company, loc, desig, period, type, summary, highlightsText, isCurrent) {
    document.getElementById('expId').value = id;
    document.getElementById('expMethod').value = 'PUT';
    document.getElementById('expCompany').value = company;
    document.getElementById('expLocation').value = loc || '';
    document.getElementById('expDesignation').value = desig;
    document.getElementById('expPeriod').value = period;
    document.getElementById('expType').value = type || 'Full-time';
    document.getElementById('expTimelineLabel').value = period;
    document.getElementById('expSummary').value = summary || '';
    document.getElementById('expHighlights').value = highlightsText || '';
    document.getElementById('expIsCurrent').checked = !!isCurrent;
    document.getElementById('expDeleteBtn').style.display = 'inline-block';
    document.getElementById('expModalTitle').textContent = 'Edit Experience: ' + company;
    openAdminModal('experienceModal');
}

async function handleExperienceSave(e) {
    e.preventDefault();
    const form = e.target;
    const id = document.getElementById('expId').value;
    const isEdit = !!id;
    const url = isEdit ? `/admin/experience/${id}` : '/admin/experience';
    const formData = new FormData(form);

    try {
        const response = await fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });

        const data = await response.json();
        if (response.ok) {
            showAdminToast(data.message || 'Experience saved successfully!');
            setTimeout(() => window.location.reload(), 600);
        } else {
            showAdminToast(data.message || 'Failed to save experience.', true);
        }
    } catch (err) {
        showAdminToast('Network error saving experience.', true);
    }
}

async function handleExperienceDelete() {
    const id = document.getElementById('expId').value;
    if (!id || !confirm('Delete this experience entry?')) return;

    try {
        const response = await fetch(`/admin/experience/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });

        const data = await response.json();
        if (response.ok) {
            showAdminToast('Experience deleted.');
            setTimeout(() => window.location.reload(), 600);
        } else {
            showAdminToast('Failed to delete experience.', true);
        }
    } catch (err) {
        showAdminToast('Error deleting experience.', true);
    }
}

// --------------------------------------------------------------------------
// 7. Education In-Place Add & Edit
// --------------------------------------------------------------------------
function openAddEducationModal() {
    document.getElementById('eduId').value = '';
    document.getElementById('eduMethod').value = 'POST';
    document.getElementById('eduInstitution').value = '';
    document.getElementById('eduDegree').value = '';
    document.getElementById('eduPeriod').value = '';
    document.getElementById('eduStatus').value = '';
    document.getElementById('eduCgpa').value = '';
    document.getElementById('eduScale').value = '4.00';
    document.getElementById('eduCoursework').value = '';
    document.getElementById('eduDeleteBtn').style.display = 'none';
    document.getElementById('eduModalTitle').textContent = 'Add Education Record';
    openAdminModal('educationModal');
}

function openEditEducationModal(id, inst, deg, period, status, cgpa, scale, coursework) {
    document.getElementById('eduId').value = id;
    document.getElementById('eduMethod').value = 'PUT';
    document.getElementById('eduInstitution').value = inst;
    document.getElementById('eduDegree').value = deg;
    document.getElementById('eduPeriod').value = period;
    document.getElementById('eduStatus').value = status;
    document.getElementById('eduCgpa').value = cgpa || '';
    document.getElementById('eduScale').value = scale || '4.00';
    document.getElementById('eduCoursework').value = coursework || '';
    document.getElementById('eduDeleteBtn').style.display = 'inline-block';
    document.getElementById('eduModalTitle').textContent = 'Edit Education: ' + inst;
    openAdminModal('educationModal');
}

async function handleEducationSave(e) {
    e.preventDefault();
    const form = e.target;
    const id = document.getElementById('eduId').value;
    const isEdit = !!id;
    const url = isEdit ? `/admin/education/${id}` : '/admin/education';
    const formData = new FormData(form);

    try {
        const response = await fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });

        const data = await response.json();
        if (response.ok) {
            showAdminToast(data.message || 'Education record saved successfully!');
            setTimeout(() => window.location.reload(), 600);
        } else {
            showAdminToast(data.message || 'Failed to save education record.', true);
        }
    } catch (err) {
        showAdminToast('Network error saving education.', true);
    }
}

async function handleEducationDelete() {
    const id = document.getElementById('eduId').value;
    if (!id || !confirm('Delete this education record?')) return;

    try {
        const response = await fetch(`/admin/education/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });

        const data = await response.json();
        if (response.ok) {
            showAdminToast('Education record deleted.');
            setTimeout(() => window.location.reload(), 600);
        } else {
            showAdminToast('Failed to delete education record.', true);
        }
    } catch (err) {
        showAdminToast('Error deleting education.', true);
    }
}

// --------------------------------------------------------------------------
// 8. Research & Highlights In-Place Add & Edit
// --------------------------------------------------------------------------
function toggleResearchFields(choice) {
    const resBlock = document.getElementById('researchFieldsBlock');
    const achBlock = document.getElementById('achievementFieldsBlock');
    if (choice === 'research') {
        resBlock.style.display = 'block';
        achBlock.style.display = 'none';
    } else {
        resBlock.style.display = 'none';
        achBlock.style.display = 'block';
    }
}

function openAddResearchModal() {
    document.getElementById('resTypeChoice').value = 'research';
    toggleResearchFields('research');
    document.getElementById('resTitle').value = '';
    document.getElementById('resSummary').value = '';
    document.getElementById('resModalTitle').textContent = 'Add Research Topic';
    openAdminModal('researchModal');
}

function openAddAchievementModal() {
    document.getElementById('resTypeChoice').value = 'achievement';
    toggleResearchFields('achievement');
    document.getElementById('resTitle').value = '';
    document.getElementById('achDesc').value = '';
    document.getElementById('resModalTitle').textContent = 'Add Achievement Highlight';
    openAdminModal('researchModal');
}

async function handleResearchSave(e) {
    e.preventDefault();
    const type = document.getElementById('resTypeChoice').value;
    const url = type === 'research' ? '/admin/research/item' : '/admin/research/achievement';
    const form = e.target;
    const formData = new FormData(form);

    try {
        const response = await fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });

        const data = await response.json();
        if (response.ok) {
            showAdminToast(data.message || 'Item saved successfully!');
            setTimeout(() => window.location.reload(), 600);
        } else {
            showAdminToast(data.message || 'Failed to save item.', true);
        }
    } catch (err) {
        showAdminToast('Network error saving item.', true);
    }
}

async function deleteResearchItem(id, title) {
    if (!confirm(`Delete research topic "${title}"?`)) return;

    try {
        const response = await fetch(`/admin/research/item/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });

        const data = await response.json();
        if (response.ok) {
            showAdminToast('Research topic deleted.');
            setTimeout(() => window.location.reload(), 600);
        }
    } catch (err) {
        showAdminToast('Failed to delete research item.', true);
    }
}

async function deleteAchievementItem(id, title) {
    if (!confirm(`Delete achievement "${title}"?`)) return;

    try {
        const response = await fetch(`/admin/research/achievement/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });

        const data = await response.json();
        if (response.ok) {
            showAdminToast('Achievement highlight deleted.');
            setTimeout(() => window.location.reload(), 600);
        }
    } catch (err) {
        showAdminToast('Failed to delete achievement.', true);
    }
}

// --------------------------------------------------------------------------
// 9. Blog Post In-Place Add & Edit
// --------------------------------------------------------------------------
function openAddBlogModal() {
    document.getElementById('blogId').value = '';
    document.getElementById('blogMethod').value = 'POST';
    document.getElementById('blogTitle').value = '';
    document.getElementById('blogSlug').value = '';
    document.getElementById('blogTags').value = '';
    document.getElementById('blogStatus').value = 'published';
    document.getElementById('blogExcerpt').value = '';
    document.getElementById('blogContent').value = '';
    document.getElementById('blogDeleteBtn').style.display = 'none';
    document.getElementById('blogModalTitle').textContent = 'Write New Article';
    openAdminModal('blogModal');
}

function openEditBlogModal(id, title, slug, tags, status, excerpt, content) {
    document.getElementById('blogId').value = id;
    document.getElementById('blogMethod').value = 'PUT';
    document.getElementById('blogTitle').value = title;
    document.getElementById('blogSlug').value = slug;
    document.getElementById('blogTags').value = tags || '';
    document.getElementById('blogStatus').value = status || 'published';
    document.getElementById('blogExcerpt').value = excerpt || '';
    document.getElementById('blogContent').value = content || '';
    document.getElementById('blogDeleteBtn').style.display = 'inline-block';
    document.getElementById('blogModalTitle').textContent = 'Edit Article: ' + title;
    openAdminModal('blogModal');
}

async function handleBlogSave(e) {
    e.preventDefault();
    const form = e.target;
    const id = document.getElementById('blogId').value;
    const isEdit = !!id;
    const url = isEdit ? `/admin/blog/${id}` : '/admin/blog';
    const formData = new FormData(form);

    try {
        const response = await fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });

        const data = await response.json();
        if (response.ok) {
            showAdminToast(data.message || 'Blog article saved successfully!');
            setTimeout(() => window.location.reload(), 600);
        } else {
            showAdminToast(data.message || 'Failed to save blog post.', true);
        }
    } catch (err) {
        showAdminToast('Network error saving article.', true);
    }
}

async function handleBlogDelete() {
    const id = document.getElementById('blogId').value;
    if (!id || !confirm('Are you sure you want to delete this article?')) return;

    try {
        const response = await fetch(`/admin/blog/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });

        const data = await response.json();
        if (response.ok) {
            showAdminToast('Article deleted successfully.');
            setTimeout(() => window.location.reload(), 600);
        } else {
            showAdminToast('Failed to delete article.', true);
        }
    } catch (err) {
        showAdminToast('Error deleting article.', true);
    }
}

// --------------------------------------------------------------------------
// 10. Resume Settings & Socials
// --------------------------------------------------------------------------
async function handleResumeSave(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    try {
        const response = await fetch('/admin/resume', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });

        const data = await response.json();
        if (response.ok) {
            showAdminToast('Resume settings & PDF updated successfully!');
            setTimeout(() => window.location.reload(), 600);
        } else {
            showAdminToast(data.message || 'Failed to update resume.', true);
        }
    } catch (err) {
        showAdminToast('Network error updating resume.', true);
    }
}

async function handleSocialsSave(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    try {
        const response = await fetch('/admin/profile/socials', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });

        const data = await response.json();
        if (response.ok) {
            showAdminToast('Social links updated successfully!');
            setTimeout(() => window.location.reload(), 600);
        } else {
            showAdminToast(data.message || 'Failed to update social links.', true);
        }
    } catch (err) {
        showAdminToast('Network error updating socials.', true);
    }
}

// --------------------------------------------------------------------------
// 11. Section Visibility Instant Toggles
// --------------------------------------------------------------------------
async function toggleSectionSetting(checkbox) {
    const section = checkbox.getAttribute('data-section');
    const enabled = checkbox.checked ? 1 : 0;

    try {
        const response = await fetch('/admin/settings/toggle-section', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ section, enabled })
        });

        const data = await response.json();
        if (response.ok && data.status === 'success') {
            showAdminToast(`Section "${section}" visibility updated!`);
        } else {
            checkbox.checked = !checkbox.checked; // revert
            showAdminToast('Failed to update section visibility.', true);
        }
    } catch (err) {
        checkbox.checked = !checkbox.checked; // revert
        showAdminToast('Network error toggling section.', true);
    }
}
