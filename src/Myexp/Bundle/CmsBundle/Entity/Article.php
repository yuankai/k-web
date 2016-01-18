<?php

namespace Myexp\Bundle\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Myexp\Bundle\CmsBundle\Helper\Upload;

/**
 * @ORM\Table(name="articles")
 * @ORM\Entity(repositoryClass="Myexp\Bundle\CmsBundle\Repository\ArticleRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Article {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255, nullable=true)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=255, nullable=true)
     */
    private $source;

    /**
     * @ORM\Column(name="publish_time", type="datetime", nullable=false)
     * @Assert\NotBlank()
     */
    private $publishTime;

    /**
     * @ORM\Column(name="create_time", type="datetime", nullable=false)
     * @Assert\NotBlank()
     */
    private $createTime;

    /**
     * @ORM\Column(name="update_time", type="datetime", nullable=true)
     */
    private $updateTime;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=true)
     */
    private $isActive;

    /**
     * @var string
     *
     * @ORM\Column(name="picurl", type="string", length=255, nullable=true)
     */
    private $picurl;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $user;

    /**
     * @Assert\Image(
     *     minWidth = 40,
     *     maxWidth = 4000,
     *     minHeight = 40,
     *     maxHeight = 4000
     * )
     */
    private $filePhoto;

    /**
     * @var string
     */
    private $tempPhoto;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Page
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Page
     */
    public function setContent($content) {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return Article
     */
    public function setAuthor($author) {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor() {
        return $this->author;
    }

    /**
     * Get from
     *
     * @return string 
     */
    public function getSource() {
        return $this->source;
    }

    /**
     * Set from
     *
     * @param string $source
     * @return Article
     */
    public function setSource($source) {
        $this->source = $source;

        return $this;
    }

    /**
     * Set publishTime
     *
     * @param string $publishTime
     * @return Article
     */
    public function setPublishTime($publishTime) {
        $this->publishTime = $publishTime;

        return $this;
    }

    /**
     * Get publishTime
     *
     * @return \DateTime  
     */
    public function getPublishTime() {
        return $this->publishTime;
    }

    /**
     * Set createTime
     *
     * @param string $createTime
     * @return Article
     */
    public function setCreateTime($createTime) {
        $this->createTime = $createTime;

        return $this;
    }

    /**
     * Get createTime
     *
     * @return \DateTime  
     */
    public function getCreateTime() {
        return $this->createTime;
    }

    /**
     * Set updateTime
     *
     * @param string $updateTime
     * @return Article
     */
    public function setUpdateTime($updateTime) {
        $this->updateTime = $updateTime;

        return $this;
    }

    /**
     * Get updateTime
     *
     * @return \DateTime  
     */
    public function getUpdateTime() {
        return $this->updateTime;
    }

    /**
     * Set picurl
     *
     * @param string $picurl
     * @return Photo
     */
    public function setPicUrl($picurl) {
        $this->picurl = $picurl;

        return $this;
    }

    /**
     * Get picurl
     *
     * @return string 
     */
    public function getPicUrl() {
        return $this->picurl;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->setCreateTime(new \DateTime());
        $this->setUpdateTime($this->getCreateTime());
    }

    /**
     * Set category
     *
     * @param \Myexp\Bundle\CmsBundle\Entity\Category $category
     * @return Article
     */
    public function setCategory(\Myexp\Bundle\CmsBundle\Entity\Category $category = null) {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Myexp\Bundle\CmsBundle\Entity\Category
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * Set user
     *
     * @param \Myexp\Bundle\CmsBundle\Entity\User $user
     * @return Article
     */
    public function setUser(\Myexp\Bundle\CmsBundle\Entity\User $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Myexp\Bundle\CmsBundle\Entity\User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Article
     */
    public function setIsActive($isActive) {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive() {
        return $this->isActive;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFilePhoto(UploadedFile $filePhoto = null) {

        $this->filePhoto = $filePhoto;

        if (isset($this->picurl)) {
            $this->tempPhoto = $this->picurl;
        }
        $this->picurl = null;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFilePhoto() {
        return $this->filePhoto;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {

        if (null !== $this->getFilePhoto()) {

            $filename = Upload:: genFileName();
            $this->picurl = $filename . '.' . $this->getFilePhoto()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        if (null !== $this->getFilePhoto()) {
            $this->getFilePhoto()->move
                    (Upload::getSavePath($this->picurl), $this->picurl);

            if (isset($this->tempPhoto)) {
                @unlink(Upload::getSavePath($this->tempPhoto) . $this->tempPhoto);
                $this->tempPhoto = null;
            }

            $this->filePhoto = null;
        }
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {

        $file = Upload::getSavePath($this->picurl) . $this->picurl;
        if (file_exists($file)) {
            @unlink($file);
        }
    }

}
