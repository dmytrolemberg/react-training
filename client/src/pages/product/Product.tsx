import { type ReactElement, useState } from 'react';
import './Product.css';
import { ROUTES } from '@/shared/model/routes';
import Button from '@/shared/ui/Button.tsx';

type ProductTab = 'details' | 'attributes';

const ATTRIBUTE_DATA = [
  ['Volume', '18L'],
  ['Laptop size', 'Up to 15”'],
  ['Material', 'Recycled textile'],
  ['Color', 'Graphite'],
  ['Finish', 'Water resistant'],
  ['Weight', '720g'],
  ['Height', '44 cm'],
  ['Width', '29 cm'],
  ['Depth', '14 cm'],
  ['Main pocket', 'Open storage'],
  ['Laptop pocket', 'Padded'],
  ['Front pocket', 'Organizer'],
  ['Back panel', 'Soft padded'],
  ['Straps', 'Adjustable'],
  ['Closure', 'Two-way zipper'],
  ['Lining', 'Recycled polyester'],
  ['Warranty', '2 years'],
  ['Season', 'All season'],
  ['Use case', 'Work / travel'],
  ['Fit', 'Unisex'],
  ['SKU', 'MORI-18-GR'],
  ['Origin', 'EU'],
  ['Care', 'Wipe clean'],
  ['Return window', '30 days'],
  ['Availability', 'In stock'],
  ['Rating', '4.9'],
  ['Reviews', '219'],
  ['Brand', 'Mori'],
  ['Category', 'Bags'],
  ['Collection', 'Everyday'],
  ['Sustainability', 'Recycled'],
  ['Hardware', 'Matte black'],
  ['Internal pockets', '4'],
  ['External pockets', '2'],
  ['Bottle holder', 'Internal'],
  ['Security pocket', 'Back zip'],
  ['Key loop', 'Included'],
  ['Compression', 'No'],
  ['Expandable', 'No'],
  ['Rain cover', 'Optional'],
  ['Gift wrap', 'Available'],
  ['Delivery', '2–4 days'],
  ['Package', 'Recyclable box'],
  ['Recommended for', 'Commuting'],
  ['Style', 'Minimal'],
  ['Texture', 'Soft matte'],
  ['Shape', 'Structured'],
  ['Load comfort', 'Medium'],
  ['Stock level', '24 items'],
  ['Last updated', 'Today'],
] as const;

const GALLERY_THUMBS = ['1', '2', '3', '4'];

function getTabButtonClassName(tab: ProductTab, activeTab: ProductTab): string {
  return tab === activeTab ? 'tab-button is-active' : 'tab-button';
}

function getTabPanelClassName(tab: ProductTab, activeTab: ProductTab): string {
  return tab === activeTab ? 'tab-panel is-active' : 'tab-panel';
}

interface ProductInformationTabsProps {
  activeTab: ProductTab;
  onTabChange: (tab: ProductTab) => void;
}

function ProductInformationTabs({
  activeTab,
  onTabChange,
}: ProductInformationTabsProps): ReactElement {
  return (
    <div className="product-information-tabs card card-pad">
      <div
        className="tab-list"
        role="tablist"
        aria-label="Product information tabs"
      >
        <button
          className={getTabButtonClassName('details', activeTab)}
          type="button"
          data-tab="details"
          onClick={() => {
            onTabChange('details');
          }}
        >
          Details
        </button>
        <button
          className={getTabButtonClassName('attributes', activeTab)}
          type="button"
          data-tab="attributes"
          onClick={() => {
            onTabChange('attributes');
          }}
        >
          Attributes
        </button>
      </div>

      <div className={getTabPanelClassName('details', activeTab)} id="details">
        <div className="rich-text wysiwyg-content" id="descriptionContent">
          <p>
            <strong>
              Clean structure, padded laptop section, front organizer, soft back
              panel, and a balanced shape for everyday use.
            </strong>
          </p>
          <p>
            This content block is designed to support a long description coming
            from a WYSIWYG editor. It can contain paragraphs, headings, lists,
            links, and formatted text without breaking the page layout or
            forcing the buy panel to become too tall.
          </p>
          <h3>Product story</h3>
          <p>
            The Everyday Carry Pack is built for work days, short trips, and
            daily commuting. The shape is intentionally simple, the pocket
            layout is predictable, and the materials are selected for durability
            and low visual noise.
          </p>
          <ul>
            <li>Separate laptop area with padded back support.</li>
            <li>
              Front organizer for cables, cards, keys, and small accessories.
            </li>
            <li>Soft textile surface with water-resistant finish.</li>
            <li>Internal volume that stays useful without looking bulky.</li>
          </ul>
          <h3>WYSIWYG example</h3>
          <p>
            A real product page may include a very long marketing description,
            care instructions, warranty text, sustainability notes, size guide
            content, or SEO text. The layout below keeps it readable and
            collapsible, so the customer still sees price, quantity, and
            checkout actions quickly.
          </p>
          <p>
            You can replace this whole block with dynamic HTML from a CMS. The
            CSS uses safe spacing, line height, list indentation, table overflow,
            and long-word wrapping to avoid layout issues.
          </p>
        </div>
      </div>

      <div
        className={getTabPanelClassName('attributes', activeTab)}
        id="attributes"
      >
        <p className="muted text-small">
          The design supports from 1 to 50 attributes as one continuous
          full-width section, without nested scrolling.
        </p>
        <div className="attributes-panel section">
          <div className="attributes-grid" id="attributeTable">
            {ATTRIBUTE_DATA.map(([name, value]) => (
              <div className="attribute-item" key={`${name}-${value}`}>
                <span>{name}</span>
                <strong>{value}</strong>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}

function Product(): ReactElement {
  const [quantity, setQuantity] = useState(1);
  const [activeTab, setActiveTab] = useState<ProductTab>('details');
  const [activeGalleryThumb, setActiveGalleryThumb] = useState('1');

  return (
    <>
      <Button className="ghost-button" to={ROUTES.PRODUCTS}>
        ← Back to catalog
      </Button>

      <section className="product-detail-layout section">
        <div className="product-gallery">
          <div className="product-media large"></div>
          <div className="gallery-thumbs">
            {GALLERY_THUMBS.map((thumb) => (
              <button
                className={
                  thumb === activeGalleryThumb
                    ? 'gallery-thumb is-active'
                    : 'gallery-thumb'
                }
                type="button"
                aria-label={`Preview image ${thumb}`}
                key={thumb}
                onClick={() => {
                  setActiveGalleryThumb(thumb);
                }}
              ></button>
            ))}
          </div>
        </div>

        <aside className="product-detail-panel">
          <div className="cluster">
            <span className="badge">Mori</span>
            <span className="badge">Bags</span>
            <span className="badge status-success">In stock</span>
          </div>
          <h1 className="title-lg section">Everyday Carry Pack</h1>
          <p className="lead product-short-description">
            A minimal 18L pack built from recycled textile, designed for
            everyday work, travel, and clean organization.
          </p>
          <div className="split section product-buy-row">
            <span className="product-price-xl">$112</span>
            <span className="rating">
              <span className="stars">★★★★★</span> 4.9 · 219 reviews
            </span>
          </div>

          <div className="section split product-action-row">
            <div className="quantity-control" aria-label="Quantity selector">
              <button
                className="quantity-button"
                type="button"
                id="minus"
                onClick={() => {
                  setQuantity((currentQuantity) =>
                    Math.max(1, currentQuantity - 1),
                  );
                }}
              >
                −
              </button>
              <span className="quantity-value" id="quantity">
                {quantity}
              </span>
              <button
                className="quantity-button"
                type="button"
                id="plus"
                onClick={() => {
                  setQuantity((currentQuantity) => currentQuantity + 1);
                }}
              >
                +
              </button>
            </div>
            <Button>
              Add to cart
            </Button>
          </div>
        </aside>
      </section>

      <section className="section product-information-section">
        <ProductInformationTabs
          activeTab={activeTab}
          onTabChange={setActiveTab}
        />
      </section>

      <section className="section">
        <div className="split">
          <div>
            <p className="eyebrow">Related</p>
            <h2 className="title-md">You may also like</h2>
          </div>
          <Button className="ghost-button" to={ROUTES.PRODUCTS}>
            View more →
          </Button>
        </div>
        <div className="grid grid-3 section">
          <article className="product-card">
            <div className="product-media"></div>
            <div className="split">
              <div>
                <h3 className="product-title">Travel Tech Pouch</h3>
                <p className="product-meta">Mori · Bags</p>
              </div>
              <span className="price">$58</span>
            </div>
            <div className="attribute-list">
              <span className="badge">2L</span>
              <span className="badge">Organizer</span>
            </div>
          </article>
          <article className="product-card">
            <div className="product-media"></div>
            <div className="split">
              <div>
                <h3 className="product-title">Aero Knit Jacket</h3>
                <p className="product-meta">Northline · Outerwear</p>
              </div>
              <span className="price">$148</span>
            </div>
            <div className="attribute-list">
              <span className="badge">Graphite</span>
              <span className="badge">Waterproof</span>
            </div>
          </article>
          <article className="product-card">
            <div className="product-media"></div>
            <div className="split">
              <div>
                <h3 className="product-title">Soft Wool Beanie</h3>
                <p className="product-meta">Northline · Accessories</p>
              </div>
              <span className="price">$36</span>
            </div>
            <div className="attribute-list">
              <span className="badge">Merino</span>
              <span className="badge">One size</span>
            </div>
          </article>
        </div>
      </section>
    </>
  );
}

export default Product;
