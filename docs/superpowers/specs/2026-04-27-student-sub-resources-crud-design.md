# Student Sub-Resources CRUD Design

**Date:** 2026-04-27  
**Status:** Approved

## Overview

Add full CRUD for five student sub-resources displayed as collapsible sections on the existing `StudentProfile` page:

1. Academic History (elementary / high school)
2. Extra-Curricular Activities
3. Violations
4. Skills
5. Organization Affiliations

---

## Data Models

All tables use auto-increment `id`, a `student_id` foreign key with `onDelete('cascade')`, and Laravel `timestamps()`.

### `student_academic_histories`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | auto-increment |
| student_id | bigint FK | cascades on delete |
| level | enum | `elementary`, `high_school` |
| school_name | string | required |
| address | string | nullable |
| year_graduated | integer (4-digit) | nullable |
| honors | string | nullable |

### `student_extra_curriculars`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| student_id | bigint FK | cascades on delete |
| name | string | required — activity name |
| role | string | nullable |
| organization | string | nullable |
| start_year | integer (4-digit) | nullable |
| end_year | integer (4-digit) | nullable |

### `student_violations`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| student_id | bigint FK | cascades on delete |
| description | text | required |
| date | date | required |
| penalty | string | nullable |
| status | enum | `pending`, `resolved`, `dismissed` — default `pending` |
| remarks | text | nullable |

### `student_skills`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| student_id | bigint FK | cascades on delete |
| name | string | required |
| category | string | nullable — e.g. "Technical", "Soft Skills" |
| proficiency | enum | nullable — `beginner`, `intermediate`, `advanced` |
| description | text | nullable |

### `student_organizations`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| student_id | bigint FK | cascades on delete |
| organization_name | string | required |
| position | string | nullable |
| type | string | nullable — e.g. "academic", "civic" |
| start_year | integer (4-digit) | nullable |
| end_year | integer (4-digit) | nullable |
| is_active | boolean | default `true` |

---

## Backend Architecture

### Routes

Nested under `students`, protected by `auth:sanctum`. No `show()` route — the profile always fetches the full list.

```
GET    /students/{student}/academic-history
POST   /students/{student}/academic-history
PUT    /students/{student}/academic-history/{history}
DELETE /students/{student}/academic-history/{history}

GET    /students/{student}/extra-curriculars
POST   /students/{student}/extra-curriculars
PUT    /students/{student}/extra-curriculars/{extraCurricular}
DELETE /students/{student}/extra-curriculars/{extraCurricular}

GET    /students/{student}/violations
POST   /students/{student}/violations
PUT    /students/{student}/violations/{violation}
DELETE /students/{student}/violations/{violation}

GET    /students/{student}/skills
POST   /students/{student}/skills
PUT    /students/{student}/skills/{skill}
DELETE /students/{student}/skills/{skill}

GET    /students/{student}/organizations
POST   /students/{student}/organizations
PUT    /students/{student}/organizations/{organization}
DELETE /students/{student}/organizations/{organization}
```

### Models

Five new Eloquent models:
- `StudentAcademicHistory`
- `StudentExtraCurricular`
- `StudentViolation`
- `StudentSkill`
- `StudentOrganization`

Each has `$fillable`, appropriate `$casts` (dates, booleans), and `belongsTo(Student::class)`.

`Student` model gains `hasMany` relations for all five.

### Controllers

Five new controllers in `app/Http/Controllers/Api/`, each following the existing pattern:
- `index(Student $student)` — returns all records for that student
- `store(Request $request, Student $student)` — validates, creates, returns 201
- `update(Request $request, Student $student, $model)` — validates, updates, returns updated record
- `destroy(Student $student, $model)` — deletes, returns 204
- `private format($model): array` — maps snake_case to camelCase for the frontend

### Validation

- Required string fields: `required|string`
- Enums: `required|in:...` or `nullable|in:...`
- 4-digit year fields: `nullable|integer|digits:4`
- Date fields: `nullable|date`
- `student_id` is never accepted from the request body — always taken from the route parameter

---

## Frontend Architecture

### Approach

All sub-resource state is **local to `StudentProfile`** — no changes to `AppContext`. On mount, the profile fires 6 parallel fetches: existing student data + 5 sub-resource lists using `Promise.all`.

API calls use `api.get/post/put/delete` from `services/api.js` with paths like `/students/${id}/academic-history`.

### UI Layout

Below the existing Personal Information and Academic Information sections, five new collapsible sections are added to `StudentProfile.jsx`:

Each section follows this pattern:
- Section header with title + "Add" button (plus icon)
- Table/list of records with edit (pencil) and delete (trash) icon buttons per row
- Empty state message when no records exist
- Add/Edit modal using the existing `Modal` and `FormInput` components
- Delete confirmation modal

### State per section (local)

```js
const [academicHistory, setAcademicHistory] = useState([]);
const [extraCurriculars, setExtraCurriculars] = useState([]);
const [violations, setViolations] = useState([]);
const [skills, setSkills] = useState([]);
const [organizations, setOrganizations] = useState([]);
```

Each section also manages its own modal visibility, selected record, and form data via local state.

### Error Handling

- Client-side validation for required fields before submit
- Server errors surfaced via the existing `useToast` / `showToast` pattern
- 401 responses handled globally by `api.js` (existing behavior)

---

## Cascade Deletes

All five migration foreign keys include `.onDelete('cascade')` so deleting a student automatically removes all related sub-records.
