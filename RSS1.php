<?php
namespace FeedWriter;

/*
 * Copyright (C) 2012 Michael Bemmerl <mail@mx-server.de>
 *
 * This file is part of the "Universal Feed Writer" project.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Wrapper for creating RSS1 feeds
 *
 * @package     UniversalFeedWriter
 */
class RSS1 extends Feed
{
    /**
    * {@inheritdoc}
    */
    public function __construct()
    {
        parent::__construct(Feed::RSS1);
    }

    /**
    * Create a new Item.
    *
    * @access   public
    * @return   Item  instance of Item class
    */
    public function createNewItem()
    {
        $Item = new RSS1Item($this->version);

        return $Item;
    }

    public function getDate()
    {
        return "";
    }
};

class RSS1Item extends Item
{
    /**
    * Set the 'description' element of feed item
    *
    * @access   public
    * @param    string  The content of 'description' or 'summary' element
    * @return   self
    */
    public function setDescription($description)
    {
        return $this->addElement('description', $description);
    }

    /**
     * Set the 'content' element of the feed item
     * For ATOM feeds only
     *
     * @access  public
     * @param   string  Content for the item (i.e., the body of a blog post).
     * @return  self
     */
    public function setContent($content)
    {
        die('The content element is supported in ATOM feeds only.');
    }

    /**
    * Set the 'title' element of feed item
    *
    * @access   public
    * @param    string  The content of 'title' element
    * @return   self
    */
    public function setTitle($title)
    {
        return $this->addElement('title', $title);
    }

    /**
    * Set the 'date' element of the feed item.
    *
    * The value of the date parameter can be either an instance of the
    * DateTime class, an integer containing a UNIX timestamp or a string
    * which is parseable by PHP's 'strtotime' function.
    *
    * @access   public
    * @param    DateTime|int|string  Date which should be used.
    * @return   self
    */
    public function setDate($date)
    {
        if (!is_numeric($date)) {
            if ($date instanceof DateTime)
                $date = $date->getTimestamp();
            else {
                $date = strtotime($date);

                if ($date === FALSE)
                    die('The given date string was not parseable.');
            }
        } elseif ($date < 0)
            die('The given date is not an UNIX timestamp.');

        $value  = date("Y-m-d", $date);
        return $this->addElement('dc:date', $value);
    }

    /**
    * Set the 'link' element of feed item
    *
    * @access   public
    * @param    string  The content of 'link' element
    * @return   void
    */
    public function setLink($link)
    {
        $this->addElement('link', $link);
        return $this;
    }

    /**
    * Attach a external media to the feed item.
    * Not supported in RSS 1.0 feeds.
    *
    * See RFC 4288 for syntactical correct MIME types.
    *
    * Note that you should avoid the use of more than one enclosure in one item,
    * since some RSS aggregators don't support it.
    *
    * @access   public
    * @param    string  The URL of the media.
    * @param    integer The length of the media.
    * @param    string  The MIME type attribute of the media.
    * @param    boolean Specifies, if multiple enclosures are allowed
    * @return   self
    * @link     https://tools.ietf.org/html/rfc4288
    */
    public function addEnclosure($url, $length, $type, $multiple = TRUE)
    {
        die('Media attachment is not supported in RSS1 feeds.');
        return $this;
    }

    /**
    * Alias of addEnclosure, for backward compatibility. Using only this
    * method ensures that the 'enclosure' element will be present only once.
    *
    * @access   public
    * @param    string  The URL of the media.
    * @param    integer The length of the media.
    * @param    string  The MIME type attribute of the media.
    * @return   self
    * @link     https://tools.ietf.org/html/rfc4288
    * @deprecated Use the addEnclosure method instead.
    *
    **/
    public function setEnclosure($url, $length, $type)
    {
        return $this->addEnclosure($url, $length, $type, false);
    }

    /**
    * Set the 'author' element of feed item.
    * Not supported in RSS 1.0 feeds.
    *
    * @access   public
    * @param    string  The author of this item
    * @param    string  Optional email address of the author
    * @param    string  Optional URI related to the author
    * @return   self
    */
    public function setAuthor($author, $email = null, $uri = null)
    {
        die('The author element is not supported in RSS1 feeds.');
        return $this;
    }

    /**
    * Set the unique identifier of the feed item
    *
    * @access   public
    * @param    string  The unique identifier of this item
    * @param    boolean The value of the 'isPermaLink' attribute in RSS 2 feeds.
    * @return   self
    */
    public function setId($id, $permaLink = false)
    {
        die('A unique ID is not supported in RSS1 feeds.');
        return $this;
    }
}
