<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ORM\Table(name="post")
 *
 */
class Post
{
    /**
     */
    public const NUM_ITEMS = 10;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="post.blank_summary")
     * @Assert\Length(max=255)
     */
    private $summary;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="post.blank_content")
     * @Assert\Length(min=10, minMessage="post.too_short_content")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     */
    private $publishedAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @var Comment[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity="Comment",
     *      mappedBy="post",
     *      orphanRemoval=true,
     *      cascade={"persist"}
     * )
     * @ORM\OrderBy({"publishedAt": "DESC"})
     */
    private $comments;

    /**
     * @var Tag[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", cascade={"persist"})
     * @ORM\JoinTable(name="post_tag")
     * @ORM\OrderBy({"name": "ASC"})
     * @Assert\Count(max="4", maxMessage="post.too_many_tags")
     */
    private $tags;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="posts", cascade={"persist"})
     */
    private $categories;


    public function __construct()
    {
        $this->publishedAt = new \DateTime();
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTime $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): void
    {
        $this->author = $author;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(?Comment $comment): void
    {
        $comment->setPost($this);
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
        }
    }

    public function removeComment(Comment $comment): void
    {
        $comment->setPost(null);
        $this->comments->removeElement($comment);
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): void
    {
        $this->summary = $summary;
    }

    public function addTag(?Tag ...$tags): void
    {
        foreach ($tags as $tag) {
            if (!$this->tags->contains($tag)) {
                $this->tags->add($tag);
            }
        }
    }

    public function removeTag(Tag $tag): void
    {
        $this->tags->removeElement($tag);
    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function __toString()
    {
        return $this.$this->title;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image): void
    {
        $this->image = $image;
    }

    public function serializer($min = false){

        if($min){
            return [
                "id"            => $this->id,
                "title"         => $this->title,
                "image"         => $this->image,
            ];
        }

        $categories = [];
        foreach ($this->categories as $categoy){
            $categories[] = [
                "id" => $categoy->getId(),
                "name" => $categoy->getName()
            ];
        }

        $tags = [];
        foreach ($this->tags as $tag){
            $tags[] = [
                "id" => $tag->getId(),
                "name" => $tag->getName()
            ];
        }

        $comments = [];
        foreach ($this->comments as $comment){
            $comments[] = [
                "id" => $comment->getId(),
                'author'        => [
                    "id"        => $comment->getAuthor()->getId(),
                    "name"  => $comment->getAuthor()->getFullName(),
                ],
                'content' => $comment->getContent(),
                'publisheAt' => $comment->getPublishedAt()
            ];
        }

        return [
            "id"            => $this->id,
            "title"         => $this->title,
            "content"       => $this->content,
            "summary"       => $this->summary,
            "image"         => $this->image,
            "publishedAt"   => $this->publishedAt,
            "tags"          => $tags,
            'author'        => [
                "id"        => $this->author->getId(),
                "name"  => $this->author->getFullName(),
            ],
            "categories" => $categories,
            "comments" => $comments
        ];
    }

    public function deserializer($data){
        $this->setTitle($data["title"]);
        $this->setSummary($data["summary"]);
        $this->setContent($data["content"]);

        $this->setImage($data["image"]);

//        foreach ($data["tags"] as $tag)
//        {
//            $t = new Tag();
//            if($tag['id'] && $tag['id'] != "") { $t->setId((int)$tag['id']); }
//            $t->setName($tag['name']);
//
//            $this->addTag($t);
//        }

        // the date update automatic
//        $date = $data["publishedAt"];
//        // Get the timestamp as the TS tring / 1000
//        $ts = (int) $date[1];
//
//        // Get the timezone name by offset
//        $tz = (int) $date[3];
//        $tz = timezone_name_from_abbr("", $tz / 100 * 3600, false);
//        $tz = new DateTimeZone($tz);
//
//        // Create a new DateTime, set the timestamp and the timezone
//        $dt = new DateTime();
//        $dt->setTimestamp($ts);
//        $dt->setTimezone($tz);
//
//        $this->setPublishedAt($dt);
//        dd($this);

    }

    /**
     * @param int $id
     *
     * @return Post
     */
    public function setId(int $id): Post
    {
        $this->id = $id;
        return $this;
}

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addPost($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
            $category->removePost($this);
        }

        return $this;
    }

}





