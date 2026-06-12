import {
  type ReactElement,
  type SyntheticEvent,
  useEffect,
  useMemo,
  useState,
} from 'react';
import './Reviews.css';

type ReviewFilter = 'all' | '5' | '4';

interface Review {
  rating: number;
  name: string;
  product: string;
  text: string;
}

interface RatingBar {
  label: string;
  percent: number;
}

interface ReviewFilterOption {
  value: ReviewFilter;
  label: string;
}

const REVIEWS: Review[] = [
  {
    rating: 5,
    name: 'Anna',
    product: 'Everyday Carry Pack',
    text: 'Very clean product details. Filters helped me find the right size and material fast.',
  },
  {
    rating: 5,
    name: 'Mark',
    product: 'Aero Knit Jacket',
    text: 'Minimal design, fast checkout, and product attributes were clear.',
  },
  {
    rating: 4,
    name: 'Sofia',
    product: 'Modular Desk Lamp',
    text: 'Good product page and very helpful rating overview.',
  },
];

const MIN_RATING = 1;
const MAX_RATING = 5;
const REVIEW_RATING_CHOICES = Array.from(
  { length: MAX_RATING },
  (_, index) => index + MIN_RATING,
);

const RATING_BARS: RatingBar[] = [
  { label: '5 stars', percent: 84 },
  { label: '4 stars', percent: 12 },
  { label: '3 stars', percent: 3 },
  { label: '2 stars', percent: 1 },
  { label: '1 star', percent: 0 },
];

const REVIEW_FILTER_OPTIONS: ReviewFilterOption[] = [
  { value: 'all', label: 'All ratings' },
  { value: '5', label: '5 stars' },
  { value: '4', label: '4 stars' },
];

function getSelectedFilterLabel(value: ReviewFilter): string {
  return (
    REVIEW_FILTER_OPTIONS.find((option) => option.value === value)?.label ??
    REVIEW_FILTER_OPTIONS[0].label
  );
}

function getStarRating(rating: number): string {
  return '★'.repeat(rating) + '☆'.repeat(MAX_RATING - rating);
}

function getPercentWidth(percent: number): string {
  return `${String(percent)}%`;
}

function Reviews(): ReactElement {
  const [reviewFilter, setReviewFilter] = useState<ReviewFilter>('all');
  const [isFilterOpen, setIsFilterOpen] = useState(false);
  const [selectedRating, setSelectedRating] = useState(0);
  const [reviewMessage, setReviewMessage] = useState('');

  const visibleReviews = useMemo((): Review[] => {
    if (reviewFilter === 'all') {
      return REVIEWS;
    }

    return REVIEWS.filter(
      (review) => String(review.rating) === reviewFilter,
    );
  }, [reviewFilter]);

  useEffect(() => {
    function closeFilter(): void {
      setIsFilterOpen(false);
    }

    function closeFilterOnEscape(event: KeyboardEvent): void {
      if (event.key === 'Escape') {
        closeFilter();
      }
    }

    document.addEventListener('click', closeFilter);
    document.addEventListener('keydown', closeFilterOnEscape);

    return (): void => {
      document.removeEventListener('click', closeFilter);
      document.removeEventListener('keydown', closeFilterOnEscape);
    };
  }, []);

  function submitReview(event: SyntheticEvent<HTMLFormElement>): void {
    event.preventDefault();

    if (selectedRating === 0) {
      setReviewMessage('Please choose a rating first.');
      return;
    }

    setReviewMessage(
      `Review submitted with ${String(
        selectedRating,
      )} stars. This is a prototype state.`,
    );
  }

  return (
    <>
      <section>
        <p className="eyebrow">Reviews & ratings</p>
        <h1 className="title-lg">Feedback that helps shoppers decide.</h1>
        <p className="lead">
          Product reviews, rating distribution, filters, and a small
          write-review form.
        </p>
      </section>

      <section className="reviews-layout section">
        <aside className="card card-pad">
          <h2 className="title-sm">Average rating</h2>
          <div className="rating-score section">
            <strong>4.8</strong>
            <span className="muted">out of 5</span>
          </div>
          <p className="rating">
            <span className="stars">★★★★★</span> Based on 452 reviews
          </p>
          <div className="divider"></div>
          {RATING_BARS.map((bar) => (
            <div className="rating-bar" key={bar.label}>
              <span>{bar.label}</span>
              <div className="rating-bar-track">
                <div
                  className="rating-bar-fill"
                  style={{ width: getPercentWidth(bar.percent) }}
                ></div>
              </div>
              <span>{getPercentWidth(bar.percent)}</span>
            </div>
          ))}
        </aside>

        <div className="stack">
          <div className="card card-pad">
            <div className="split">
              <h2 className="title-sm">Customer reviews</h2>
              <div
                className={`custom-select review-filter-select ${
                  isFilterOpen ? 'is-open' : ''
                }`}
                data-custom-select
                onClick={(event) => {
                  event.stopPropagation();
                }}
              >
                <input
                  type="hidden"
                  id="reviewFilter"
                  value={reviewFilter}
                  readOnly
                />
                <button
                  className="custom-select-button"
                  type="button"
                  aria-haspopup="listbox"
                  aria-expanded={isFilterOpen}
                  onClick={() => {
                    setIsFilterOpen((isOpen) => !isOpen);
                  }}
                >
                  <span data-selected-label>
                    {getSelectedFilterLabel(reviewFilter)}
                  </span>
                </button>
                <div
                  className="custom-select-menu custom-select-menu-right"
                  role="listbox"
                  aria-label="Review rating filter"
                >
                  {REVIEW_FILTER_OPTIONS.map((option) => {
                    const isSelected = option.value === reviewFilter;

                    return (
                      <button
                        className={`custom-select-option ${
                          isSelected ? 'is-selected' : ''
                        }`}
                        type="button"
                        data-value={option.value}
                        role="option"
                        aria-selected={isSelected}
                        key={option.value}
                        onClick={() => {
                          setReviewFilter(option.value);
                          setIsFilterOpen(false);
                        }}
                      >
                        {option.label}
                      </button>
                    );
                  })}
                </div>
              </div>
            </div>
            <div className="stack section" id="reviewList">
              {visibleReviews.map((review) => (
                <article
                  className="review-card"
                  key={`${review.name}-${review.product}`}
                >
                  <div className="split">
                    <div>
                      <strong>{review.name}</strong>
                      <p className="product-meta">{review.product}</p>
                    </div>
                    <span className="rating">
                      <span className="stars">
                        {getStarRating(review.rating)}
                      </span>
                    </span>
                  </div>
                  <p className="muted text-small section">“{review.text}”</p>
                </article>
              ))}
            </div>
          </div>

          <form className="card card-pad" id="reviewForm" onSubmit={submitReview}>
            <h2 className="title-sm">Write a review</h2>
            <div className="form-field section">
              <label>Rating</label>
              <div className="star-input" id="starInput">
                {REVIEW_RATING_CHOICES.map((rating) => (
                  <button
                    className={`star-choice ${
                      rating <= selectedRating ? 'is-active' : ''
                    }`}
                    type="button"
                    data-value={rating}
                    aria-label={`${String(rating)} star rating`}
                    key={rating}
                    onClick={() => {
                      setSelectedRating(rating);
                    }}
                  >
                    ★
                  </button>
                ))}
              </div>
            </div>
            <div className="form-field section">
              <label htmlFor="reviewText">Review</label>
              <textarea
                className="textarea"
                id="reviewText"
                placeholder="Share your experience..."
                required
              ></textarea>
            </div>
            <button className="primary-button section" type="submit">
              Submit review
            </button>
            <p className="muted text-small" id="reviewMessage">
              {reviewMessage}
            </p>
          </form>
        </div>
      </section>
    </>
  );
}

export default Reviews;
