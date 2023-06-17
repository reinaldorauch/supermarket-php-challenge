CREATE TABLE "users" (
    "id"           BIGSERIAL PRIMARY KEY NOT NULL,
    "username"     VARCHAR(255) NOT NULL,
    "firstName"    VARCHAR(255) NOT NULL,
    "lastName"     VARCHAR(255) NOT NULL,
    "passwordHash" VARCHAR(255) NOT NULL,
    "createdAt"    TIMESTAMP WITH TIME ZONE NOT NULL,
    "updatedAt"    TIMESTAMP WITH TIME ZONE NOT NULL,
    "deletedAt"    TIMESTAMP WITH TIME ZONE
);

CREATE TABLE "product_type" (
    "id"        BIGSERIAL PRIMARY KEY NOT NULL,
    "name"      VARCHAR(255) NOT NULL,
    "taxRate"   SMALLINT NOT NULL,
    "createdAt" TIMESTAMP WITH TIME ZONE NOT NULL,
    "updatedAt" TIMESTAMP WITH TIME ZONE NOT NULL,
    "deletedAt" TIMESTAMP WITH TIME ZONE
);

CREATE TABLE "product" (
    "id"            BIGSERIAL PRIMARY KEY NOT NULL,
    "name"          VARCHAR(255) NOT NULL,
    "price"         BIGINT NOT NULL,
    "productTypeId" BIGINT REFERENCES "product_type" ("id"),
    "createdAt" TIMESTAMP WITH TIME ZONE NOT NULL,
    "updatedAt" TIMESTAMP WITH TIME ZONE NOT NULL,
    "deletedAt" TIMESTAMP WITH TIME ZONE
);

CREATE TABLE "checkout_cart" (
    "id"        BIGSERIAL PRIMARY KEY NOT NULL,
    "createdAt" TIMESTAMP WITH TIME ZONE NOT NULL,
    "updatedAt" TIMESTAMP WITH TIME ZONE NOT NULL,
    "deletedAt" TIMESTAMP WITH TIME ZONE
);

CREATE TABLE "checkout_cart_product" (
    "productId" BIGINT REFERENCES "product" ("id"),
    "checkoutCartId" BIGINT REFERENCES "checkout_cart" ("id"),
    "quantity" SMALLINT NOT NULL,
    "chargedPrice" BIGINT NOT NULL,
    "chargedTotalPrice" BIGINT NOT NULL,
    "chargedTax" BIGINT NOT NULL,
    "chargedTotalTax" BIGINT NOT NULL,
    "createdAt" TIMESTAMP WITH TIME ZONE NOT NULL,
    "updatedAt" TIMESTAMP WITH TIME ZONE NOT NULL,
    "deletedAt" TIMESTAMP WITH TIME ZONE,
    PRIMARY KEY ("productId", "checkoutCartId")
);

