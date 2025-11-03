[Tundra Tax & Accounting - Iterative Development Process Documentation.md](https://github.com/user-attachments/files/23216616/Tundra.Tax.Accounting.-.Iterative.Development.Process.Documentation.md)
# Tundra Tax & Accounting - Iterative Development Process Documentation

*A production-ready web-based company registration and management system built with PHP, MySQL, and modern development practices.*

---

## Project Overview

This repository documents the complete iterative development journey of **Tundra Tax & Accounting**, a web application designed to streamline company registration, tax compliance workflows, and document management for accounting firms and their clients.

The system evolves through three distinct development phases (`Push 1`, `Push 2`, `Push 3`), each representing a critical milestone in transforming a raw prototype into a production-grade enterprise solution.

By preserving all three phases in the repository, we provide transparent evidence of process maturity. This allows stakeholders to:
- Trace decision-making at every stage
- Understand trade-offs between speed, quality, and scalability
- Observe how feedback was systematically integrated
- Evaluate the team’s ability to adapt, refactor, and deliver under real-world constraints

Including all three phases demonstrates strong communication and stakeholder alignment. Version control becomes a narrative tool that shows concerns were not ignored but resolved through visible, incremental improvement. This level of transparency fosters trust and reflects a collaborative development culture.

## Team members 
### Developers:
- **Michael Dos Remendos**: ST10039888
- **Eesan Pather**: ST10257419
- **Areeb Ibrahim**: ST10358799
- **Regardt Van Der Riet**: ST10248015
- **Rahil Sirkissoon**: ST10388332

---

## Repository Structure

```
├── Push 1/                  # Initial Prototype Phase
├── Push 2/                  # Structure Refactoring Phase  
└── Push 3/                  # Production-Ready Phase (Current Main Branch)
```

The `main` branch reflects the final state of **Push 3**. Use tagged releases or branch comparisons to explore the evolution.

---

## Development Philosophy

The project follows a deliberate iterative methodology based on the following principles:

- Begin with rapid prototyping to validate core functionality and gather early feedback
- Refactor systematically to improve structure before adding complexity
- Harden the system with security, error handling, and operational controls only after a stable foundation exists
- Document each phase to maintain clarity and enable future maintenance

This approach reduces risk, accelerates value delivery, and ensures alignment with business needs at every step.

---

## Phase 1 – Initial Prototype (Push 1)

### Objective
Deliver a working proof-of-concept to validate core business requirements and secure stakeholder approval.

### Project State
A functional prototype developed in under 72 hours using vanilla PHP and basic HTML/CSS. All files reside in the root directory with no architectural separation.

### Key Characteristics

| Strength | Limitation |
|--------|-----------|
| Rapid feature implementation | Flat file structure |
| Direct stakeholder demonstrations | No error handling |
| Clear business logic flow | Hardcoded configuration |
| End-to-end operational workflow | Minimal input validation |

### Core Features Implemented
- User registration and authentication
- Multi-step company registration process
- Basic database CRUD operations
- PDF certificate generation using DomPDF
- Email notifications using PHPMailer
- File upload functionality

### Technical Limitations Identified
- Poor code organization impacting maintainability
- Absence of centralized error handling
- Security vulnerabilities in input processing
- Inconsistent file naming and path management
- Mixed presentation and logic in single files
- No logging or debugging mechanisms
- Hardcoded configuration values
- Limited scalability potential

### Value Delivered
- Demonstrated core functionality to stakeholders
- Validated technical feasibility
- Established user workflow requirements
- Identified critical business processes and data fields

---

## Phase 2 – Structural Refactoring (Push 2)

### Objective
Address maintainability and scalability issues identified in Push 1 through systematic code restructuring.

### Evolution Strategy
New features were frozen. Refactoring occurred incrementally with manual regression testing after each change to preserve functionality.

### Directory Structure Implementation
```
├── assets/
│   ├── css/          # Organized stylesheet modules
│   └── images/       # Centralized image assets
├── includes/
│   ├── config.php    # Centralized configuration management
│   └── db.php        # Database connection abstraction
├── pages/
│   ├── auth/         # Authentication-related scripts
│   ├── company/      # Business logic and workflows
│   └── public/       # Public-facing pages
├── uploads/          # Secure file storage directory
└── vendor/           # Composer-managed dependencies
```

### Technical Improvements
- Separation of concerns across logical modules
- Centralized configuration system with environment awareness
- Standardized database connection handling
- Consistent file naming conventions (`snake_case`)
- Improved path management using `BASE_PATH` constants
- Introduction of Composer for dependency management
- Basic security measures (output encoding, upload type checks)

### Code Quality Enhancements
- Reduced mixing of HTML and PHP
- Predictable include/require patterns
- Improved readability and navigation
- Foundation for team collaboration and parallel development

---

## Phase 3 – Production-Ready System (Push 3)

### Objective
Transform the refactored prototype into a secure, reliable, and operationally excellent enterprise application.

This is the current production version — fully tested, audited, and deployment-ready.

---

### Security Implementation

| Threat | Mitigation |
|------|----------|
| SQL Injection | Prepared statements with PDO |
| Cross-Site Scripting (XSS) | Output encoding and content security considerations |
| Malicious File Uploads | MIME type validation, extension checks, filename sanitization |
| Session Attacks | Secure, HttpOnly, and regenerated session IDs |
| Information Disclosure | Error display disabled in production |
| Brute Force Login | Framework-ready for rate limiting |

---

### Error Handling and Resilience

#### Centralized Error Management
- `ErrorHandler` class for consistent exception and error capture
- Structured logging to `logs/app.log` with timestamps and context
- User-friendly error messages without technical exposure
- Automatic database transaction rollback on failure

#### Input Validation
- `Validator` class with reusable rules:
  - Required fields
  - Email format
  - Canadian Business Number (CRA BN) format
  - File type and size restrictions

---

### Operational Excellence

| Feature | Implementation |
|-------|--------------|
| Automated Setup | `setup.php` creates database and tables |
| Environment Configuration | Toggle between development and production modes |
| Debug Control | `DEBUG` constant enables/disables verbose output |
| Logging System | File-based logs with rotation readiness |
| Dependency Management | Composer `autoload.php` integration |

---

### Advanced Features with Reliability

| Feature | Enhancement |
|-------|-----------|
| PDF Generation | Error handling, temporary file cleanup |
| Email Delivery | Attachment support, graceful failure |
| File Uploads | Validation, secure naming, storage isolation |
| Database Operations | Transaction support for data integrity |
| Session Management | Automatic expiry and privilege-aware regeneration |

---

### Quality Assurance

#### Testing Approach
- Unit tests for core components (`Validator`, `PDFGenerator`)
- Integration tests for end-to-end registration flow
- Manual cross-browser and mobile responsiveness testing

#### Code Standards
- Consistent error handling patterns
- Comprehensive inline documentation
- Security annotations on sensitive operations
- Adherence to maintainable PHP practices

---

## Technical Stack

| Layer | Technology |
|------|-----------|
| Backend | PHP 7.4+, PDO, Composer |
| Frontend | HTML5, CSS3, Vanilla JavaScript |
| Database | MySQL 8.0+ |
| PDF Generation | DomPDF |
| Email | PHPMailer |
| Server Environment | XAMPP, Apache, or Nginx compatible |

---

## Development Process Summary

The three-phase approach forms a structured improvement cycle:

1. **Rapid Prototyping (Push 1)**  
   Quick implementation, stakeholder validation, requirement clarification
2. **Structural Refactoring (Push 2)**  
   Code organization, architecture enhancement, maintainability focus
3. **Production Hardening (Push 3)**  
   Security integration, error resilience, operational readiness

This method ensures that feedback drives development, technical debt is managed early, and the final system is both functional and sustainable.

---

## Setup Instructions

### Prerequisites
- XAMPP or LAMP stack
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Composer

### Installation Steps
```bash
# Clone repository and enter Push 3
git clone https://github.com/yourusername/tundra-tax-accounting.git
cd "tundra-tax-accounting/Push 3"

# Install dependencies
composer install

# Run database setup
php setup.php

# Access application
http://localhost/tundra-tax-accounting/Push%203/pages/public/
```

Detailed configuration and troubleshooting steps are available in `SETUP.md` within the Push 3 directory.

---
