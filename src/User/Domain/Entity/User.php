<?php

declare(strict_types=1);

namespace App\User\Domain\Entity;

use App\Auth\Application\Event\SignUp\AfterUserSignUpEvent;
use App\Cart\Application\Exception\ProductWasNotAddedToCartException;
use App\Cart\Domain\Entity\CartProduct;
use App\Common\Domain\Entity\AbstractBaseEntity;
use App\Common\Domain\Trait\HasDatetime;
use App\Common\Domain\Trait\HasId;
use App\Common\Domain\ValueObject\Email;
use App\Common\Domain\ValueObject\RuPhoneNumber;
use App\Order\Domain\Entity\Order;
use App\Product\Domain\Entity\Product;
use App\Role\Domain\Entity\Role;
use App\User\Application\Exception\ProductAlreadyAddedToCartException;
use App\User\Domain\ValueObject\Name;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
#[ORM\HasLifecycleCallbacks]
class User extends AbstractBaseEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    use HasId;
    use HasDatetime;

    #[ORM\Embedded(class: Name::class, columnPrefix: false)]
    private Name $name;

    #[ORM\Embedded(class: Email::class, columnPrefix: false)]
    private Email $email;

    #[ORM\Embedded(class: RuPhoneNumber::class, columnPrefix: false)]
    private RuPhoneNumber $phone;

    #[ORM\Column(type: 'uuid', nullable: true)]
    private ?UuidV4 $promoId;

    /**
     * The password must be set after creating an instance to generate a hash using PasswordAuthenticatedUserInterface.
     */
    #[ORM\Column(type: 'string')]
    private ?string $password = null;

    #[ORM\JoinTable(name: 'roles_users')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\InverseJoinColumn(name: 'role_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\ManyToMany(targetEntity: Role::class)]
    private Collection $roles;

    #[ORM\OneToMany(
        mappedBy: 'user',
        targetEntity: Order::class,
        orphanRemoval: true,
        cascade: [
            'persist',
        ],
    )]
    private Collection $orders;

    #[ORM\OneToMany(
        mappedBy: 'user',
        targetEntity: CartProduct::class,
        orphanRemoval: true,
        cascade: [
            'persist',
        ],
    )]
    private Collection $cartProducts;

    protected function __construct()
    {
        $this->cartProducts = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public static function create(
        Name $name,
        Email $email,
        RuPhoneNumber $phone,
        ?UuidV4 $promoId,
        Collection $roles = new ArrayCollection(),
        DateTimeImmutable $createdAt = new DateTimeImmutable(),
        DateTimeImmutable $updatedAt = new DateTimeImmutable(),
    ): self {
        $user = (new self())
            ->setId(new UuidV4())
            ->setName($name)
            ->setEmail($email)
            ->setPhone($phone)
            ->setPromoId($promoId)
            ->setRoles($roles)
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($updatedAt);

        $user->recordEvent(new AfterUserSignUpEvent(
            email: $email->getEmail(),
            phone: $phone->getPhone(),
            promoId: $promoId?->toString(),
        ));

        return $user;
    }

    public function checkoutOrder(Order $order): void
    {
        $this->orders->add($order);
        $order->addOrderProductsFromCartProducts($this->cartProducts);
        $this->cartProducts->clear();
    }

    /**
     * @throws ProductAlreadyAddedToCartException
     */
    public function addProductToCart(Product $product, int $quantity = 1): void
    {
        $hasProductAlreadyBeenAddedToCart = $this->hasProductInCart($product);
        if (true === $hasProductAlreadyBeenAddedToCart) {
            throw ProductAlreadyAddedToCartException::byId($product->getId()->toString());
        }

        $cartProduct = CartProduct::create(
            user: $this,
            product: $product,
            quantity: $quantity
        );

        $this->cartProducts->add($cartProduct);
    }

    /**
     * @throws ProductWasNotAddedToCartException
     */
    public function getCartProductByProduct(Product $product): CartProduct
    {
        $cartProduct = $this->cartProducts->findFirst(function (int $index, CartProduct $cartProduct) use ($product) {
            return $cartProduct->getProduct() === $product;
        });

        if (null === $cartProduct) {
            throw ProductWasNotAddedToCartException::byId($product->getId()->toString());
        }

        return $cartProduct;
    }

    /**
     * @throws ProductWasNotAddedToCartException
     */
    public function getProductByIdFromCart(string $productId): Product
    {
        $cartProduct = $this->cartProducts->findFirst(function (int $index, CartProduct $cartProduct) use ($productId) {
            return $cartProduct->getProduct()->getId()->toString() === $productId;
        });

        if (null === $cartProduct) {
            throw ProductWasNotAddedToCartException::byId($productId);
        }

        return $cartProduct->getProduct();
    }

    /**
     * @throws ProductWasNotAddedToCartException
     */
    public function changeProductQuantityInCart(Product $product, int $quantity): void
    {
        $cartProduct = $this->getCartProductByProduct($product);
        $cartProduct->setQuantity($quantity);
    }

    /**
     * @throws ProductWasNotAddedToCartException
     */
    public function removeProductFromCart(Product $product): void
    {
        $cartProduct = $this->getCartProductByProduct($product);

        $this->cartProducts->removeElement($cartProduct);
    }

    public function hasProductInCart(Product $product): bool
    {
        return $this->cartProducts->exists(function (int $index, CartProduct $cartProduct) use ($product) {
            return $cartProduct->getProduct() === $product;
        });
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->email->getEmail();
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function setName(Name $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setEmail(Email $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setPhone(RuPhoneNumber $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function setPromoId(?UuidV4 $promoId): self
    {
        $this->promoId = $promoId;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return $this->roles->map(fn (Role $role) => $role->getSlug())->toArray();
    }

    public function setRoles(Collection $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getCartProducts(): Collection
    {
        return $this->cartProducts;
    }
}
