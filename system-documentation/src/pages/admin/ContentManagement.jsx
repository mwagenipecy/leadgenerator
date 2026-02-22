import StepCard from '../../components/StepCard'

export default function ContentManagement() {
  return (
    <article>
      <h1 className="mb-2 text-3xl font-bold text-slate-900">Super Admin – Content Management</h1>
      <p className="mb-8 text-slate-600">
        Content Management covers Loan Categories, Blog, Hero Slider, and Promotions. All are available from the sidebar when logged in as Super Admin.
      </p>

      <h2 className="mb-4 text-xl font-semibold text-slate-900">Loan Categories</h2>
      <StepCard step={1} title="Open Loan Categories">
        In the sidebar, click <strong>Loan Categories</strong>. You see the list of categories used to classify loan products (e.g. Personal, Business).
      </StepCard>
      <StepCard step={2} title="Add or edit categories">
        Use <strong>Create</strong> to add a new category (name, description, order). Use <strong>Edit</strong> to change or <strong>Disable</strong> to hide a category from selection. Save after each change.
      </StepCard>

      <h2 className="mb-4 mt-10 text-xl font-semibold text-slate-900">Blog Management</h2>
      <StepCard step={3} title="Open Blog Management">
        In the sidebar, click <strong>Blog Management</strong>. You see the list of blog posts (title, status, date).
      </StepCard>
      <StepCard step={4} title="Create or edit a post">
        Click <strong>Create</strong> to add a new post: title, slug, content, excerpt, featured image, and publish status. Click <strong>Edit</strong> on an existing post to update it. Save or Publish when done.
      </StepCard>

      <h2 className="mb-4 mt-10 text-xl font-semibold text-slate-900">Hero Slider</h2>
      <StepCard step={5} title="Open Hero Slider Management">
        In the sidebar, click <strong>Hero Slider</strong>. You see the slides shown on the landing page hero section.
      </StepCard>
      <StepCard step={6} title="Manage slides">
        Add a slide with image, title, subtitle, link, and order. Edit or delete existing slides. Reorder slides to change display order. Active slides are shown on the landing page.
      </StepCard>

      <h2 className="mb-4 mt-10 text-xl font-semibold text-slate-900">Promotions</h2>
      <StepCard step={7} title="Open Promotion Management">
        In the sidebar, click <strong>Promotions</strong>. You see the list of promotions or banners.
      </StepCard>
      <StepCard step={8} title="Manage promotions">
        Create a promotion with title, content, image, link, start/end dates, and visibility. Edit or disable existing promotions. Promotions are shown on the site according to their schedule and targeting.
      </StepCard>
    </article>
  )
}
